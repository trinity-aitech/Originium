<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Gerador de QR Code 100% em PHP puro (sem GD, sem dependências externas).
 *
 * Modo byte, nível de correção M, versões 1–10 (suficiente para URLs).
 * Algoritmo portado da referência MIT de Nayuki (Reed–Solomon, máscaras,
 * informação de formato/versão). A saída PNG é montada à mão via zlib.
 */
final class QrCode
{
    /** Nº de blocos de correção por versão (nível M). */
    private const EC_BLOCKS = [0, 1, 1, 1, 2, 2, 4, 4, 4, 5, 5];
    /** Codewords de correção por bloco (nível M). */
    private const EC_PER_BLOCK = [0, 10, 16, 26, 18, 24, 16, 18, 22, 22, 26];

    private int $version;
    private int $size;
    public int $chosenMask = -1;
    /** @var array<int,array<int,int>> modules[y][x] = 0|1 */
    private array $m = [];
    /** @var array<int,array<int,bool>> */
    private array $fn = [];

    private function __construct(int $version)
    {
        $this->version = $version;
        $this->size = $version * 4 + 17;
        for ($y = 0; $y < $this->size; $y++) {
            $this->m[$y] = array_fill(0, $this->size, 0);
            $this->fn[$y] = array_fill(0, $this->size, false);
        }
    }

    /** Gera o PNG (bytes) do conteúdo informado. */
    public static function png(string $text, int $scale = 8, int $margin = 4): string
    {
        $qr = self::encode($text);
        return $qr->toPng($scale, $margin);
    }

    /** Constrói a matriz final (com melhor máscara aplicada). */
    public static function encode(string $text): self
    {
        $bytes = array_values(unpack('C*', $text) ?: []);
        $version = self::chooseVersion(count($bytes));

        $qr = new self($version);
        $data = $qr->buildCodewords($bytes);

        $qr->drawFunctionPatterns();
        $qr->drawCodewords($data);

        // Escolhe a máscara de menor penalidade
        $bestMask = 0;
        $minPenalty = PHP_INT_MAX;
        for ($mask = 0; $mask < 8; $mask++) {
            $qr->applyMask($mask);
            $qr->drawFormatBits($mask);
            $penalty = $qr->penaltyScore();
            if ($penalty < $minPenalty) {
                $minPenalty = $penalty;
                $bestMask = $mask;
            }
            $qr->applyMask($mask); // desfaz (XOR é involutivo)
        }
        $qr->applyMask($bestMask);
        $qr->drawFormatBits($bestMask);
        $qr->chosenMask = $bestMask;

        return $qr;
    }

    public function matrix(): array
    {
        return $this->m;
    }

    // ─── Seleção de versão e fluxo de bits ──────────────────────────────

    private static function dataCapacityCodewords(int $version): int
    {
        $raw = intdiv(self::rawDataModules($version), 8);
        return $raw - self::EC_BLOCKS[$version] * self::EC_PER_BLOCK[$version];
    }

    private static function rawDataModules(int $version): int
    {
        $result = (16 * $version + 128) * $version + 64;
        if ($version >= 2) {
            $numAlign = intdiv($version, 7) + 2;
            $result -= (25 * $numAlign - 10) * $numAlign - 55;
            if ($version >= 7) {
                $result -= 36;
            }
        }
        return $result;
    }

    private static function chooseVersion(int $len): int
    {
        for ($v = 1; $v <= 10; $v++) {
            $cci = $v < 10 ? 8 : 16;
            $needBits = 4 + $cci + 8 * $len;
            if (self::dataCapacityCodewords($v) * 8 >= $needBits) {
                return $v;
            }
        }
        throw new \RuntimeException('Conteúdo grande demais para o QR (máx. versão 10).');
    }

    /** @param int[] $bytes */
    private function buildCodewords(array $bytes): array
    {
        $len = count($bytes);
        $cci = $this->version < 10 ? 8 : 16;
        $capacity = self::dataCapacityCodewords($this->version);

        $bits = [];
        $push = static function (int $val, int $n) use (&$bits): void {
            for ($i = $n - 1; $i >= 0; $i--) {
                $bits[] = ($val >> $i) & 1;
            }
        };

        $push(0b0100, 4);          // modo byte
        $push($len, $cci);         // contador de caracteres
        foreach ($bytes as $b) {
            $push($b, 8);
        }

        $capacityBits = $capacity * 8;
        // Terminador
        for ($i = 0; $i < 4 && count($bits) < $capacityBits; $i++) {
            $bits[] = 0;
        }
        // Alinha ao byte
        while (count($bits) % 8 !== 0) {
            $bits[] = 0;
        }
        // Converte em codewords
        $codewords = [];
        for ($i = 0; $i < count($bits); $i += 8) {
            $byte = 0;
            for ($j = 0; $j < 8; $j++) {
                $byte = ($byte << 1) | $bits[$i + $j];
            }
            $codewords[] = $byte;
        }
        // Bytes de preenchimento
        $pad = [0xEC, 0x11];
        $k = 0;
        while (count($codewords) < $capacity) {
            $codewords[] = $pad[$k % 2];
            $k++;
        }

        return $this->addEccAndInterleave($codewords);
    }

    /** @param int[] $data */
    private function addEccAndInterleave(array $data): array
    {
        $version = $this->version;
        $numBlocks = self::EC_BLOCKS[$version];
        $blockEccLen = self::EC_PER_BLOCK[$version];
        $rawCodewords = intdiv(self::rawDataModules($version), 8);
        $numShort = $numBlocks - $rawCodewords % $numBlocks;
        $shortLen = intdiv($rawCodewords, $numBlocks);

        $divisor = self::rsDivisor($blockEccLen);
        $blocks = [];
        $k = 0;
        for ($i = 0; $i < $numBlocks; $i++) {
            $datLen = $shortLen - $blockEccLen + ($i < $numShort ? 0 : 1);
            $dat = array_slice($data, $k, $datLen);
            $k += $datLen;
            $ecc = self::rsRemainder($dat, $divisor);
            if ($i < $numShort) {
                $dat[] = 0; // célula de preenchimento (pulada na intercalação)
            }
            $blocks[] = array_merge($dat, $ecc);
        }

        $result = [];
        $blockLen = count($blocks[0]);
        for ($i = 0; $i < $blockLen; $i++) {
            foreach ($blocks as $j => $block) {
                if ($i !== $shortLen - $blockEccLen || $j >= $numShort) {
                    $result[] = $block[$i];
                }
            }
        }
        return $result;
    }

    // ─── Reed–Solomon (GF(256), 0x11D) ──────────────────────────────────

    private static function gfMul(int $x, int $y): int
    {
        $z = 0;
        for ($i = 7; $i >= 0; $i--) {
            $z = ($z << 1) ^ (($z >> 7) * 0x11D);
            $z ^= (($y >> $i) & 1) * $x;
        }
        return $z & 0xFF;
    }

    private static function rsDivisor(int $degree): array
    {
        $result = array_fill(0, $degree, 0);
        $result[$degree - 1] = 1;
        $root = 1;
        for ($i = 0; $i < $degree; $i++) {
            for ($j = 0; $j < $degree; $j++) {
                $result[$j] = self::gfMul($result[$j], $root);
                if ($j + 1 < $degree) {
                    $result[$j] ^= $result[$j + 1];
                }
            }
            $root = self::gfMul($root, 0x02);
        }
        return $result;
    }

    /** @param int[] $data @param int[] $divisor */
    private static function rsRemainder(array $data, array $divisor): array
    {
        $degree = count($divisor);
        $result = array_fill(0, $degree, 0);
        foreach ($data as $b) {
            $factor = $b ^ array_shift($result);
            $result[] = 0;
            foreach ($divisor as $i => $coef) {
                $result[$i] ^= self::gfMul($coef, $factor);
            }
        }
        return $result;
    }

    // ─── Desenho de módulos (x = coluna, y = linha) ─────────────────────

    private function set(int $x, int $y, int $val): void
    {
        $this->m[$y][$x] = $val & 1;
        $this->fn[$y][$x] = true;
    }

    private function drawFunctionPatterns(): void
    {
        $size = $this->size;
        // Timing
        for ($i = 0; $i < $size; $i++) {
            $this->set(6, $i, ($i + 1) % 2);
            $this->set($i, 6, ($i + 1) % 2);
        }
        // Finders + separadores
        $this->drawFinder(3, 3);
        $this->drawFinder($size - 4, 3);
        $this->drawFinder(3, $size - 4);

        // Padrões de alinhamento
        $pos = $this->alignPositions();
        $n = count($pos);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if (($i === 0 && $j === 0) || ($i === 0 && $j === $n - 1) || ($i === $n - 1 && $j === 0)) {
                    continue; // cantos ocupados pelos finders
                }
                $this->drawAlignment($pos[$i], $pos[$j]);
            }
        }

        // Reserva áreas de formato/versão (valores definidos depois)
        $this->reserveFormatAndVersion();
    }

    private function drawFinder(int $cx, int $cy): void
    {
        for ($dy = -4; $dy <= 4; $dy++) {
            for ($dx = -4; $dx <= 4; $dx++) {
                $x = $cx + $dx;
                $y = $cy + $dy;
                if ($x < 0 || $x >= $this->size || $y < 0 || $y >= $this->size) {
                    continue;
                }
                $dist = max(abs($dx), abs($dy));
                $this->set($x, $y, ($dist !== 2 && $dist !== 4) ? 1 : 0);
            }
        }
    }

    private function drawAlignment(int $cx, int $cy): void
    {
        for ($dy = -2; $dy <= 2; $dy++) {
            for ($dx = -2; $dx <= 2; $dx++) {
                $this->set($cx + $dx, $cy + $dy, max(abs($dx), abs($dy)) !== 1 ? 1 : 0);
            }
        }
    }

    private function alignPositions(): array
    {
        if ($this->version === 1) {
            return [];
        }
        $numAlign = intdiv($this->version, 7) + 2;
        $step = (int) ceil(($this->size - 13) / (2 * $numAlign - 2)) * 2;
        $result = [6];
        for ($pos = $this->size - 7; count($result) < $numAlign; $pos -= $step) {
            array_splice($result, 1, 0, [$pos]);
        }
        return $result;
    }

    private function reserveFormatAndVersion(): void
    {
        $size = $this->size;
        // Formato (apenas marca como função; valor definido em drawFormatBits)
        for ($i = 0; $i <= 5; $i++) {
            $this->fn[$i][8] = true;
        }
        $this->fn[7][8] = $this->fn[8][8] = $this->fn[8][7] = true;
        for ($i = 0; $i <= 5; $i++) {
            $this->fn[8][$i] = true;
        }
        for ($i = 0; $i < 8; $i++) {
            $this->fn[$size - 1 - $i][8] = true;       // coluna 8, linhas size-1..size-8 (inclui módulo escuro)
        }
        for ($i = 0; $i < 8; $i++) {
            $this->fn[8][$size - 1 - $i] = true;       // linha 8, colunas size-1..size-8
        }
        $this->set(8, $size - 8, 1); // módulo escuro fixo

        // Versão (>= 7)
        if ($this->version >= 7) {
            $rem = $this->version;
            for ($i = 0; $i < 12; $i++) {
                $rem = ($rem << 1) ^ (($rem >> 11) * 0x1F25);
            }
            $bits = ($this->version << 12) | $rem;
            for ($i = 0; $i < 18; $i++) {
                $bit = ($bits >> $i) & 1;
                $a = $size - 11 + $i % 3;
                $b = intdiv($i, 3);
                $this->set($a, $b, $bit);
                $this->set($b, $a, $bit);
            }
        }
    }

    /** @param int[] $data codewords intercalados */
    private function drawCodewords(array $data): void
    {
        $size = $this->size;
        $i = 0; // índice de bit
        $total = count($data) * 8;
        for ($right = $size - 1; $right >= 1; $right -= 2) {
            if ($right === 6) {
                $right = 5;
            }
            for ($vert = 0; $vert < $size; $vert++) {
                for ($j = 0; $j < 2; $j++) {
                    $x = $right - $j;
                    $upward = (($right + 1) & 2) === 0;
                    $y = $upward ? $size - 1 - $vert : $vert;
                    if (!$this->fn[$y][$x] && $i < $total) {
                        $this->m[$y][$x] = ($data[$i >> 3] >> (7 - ($i & 7))) & 1;
                        $i++;
                    }
                }
            }
        }
    }

    private function applyMask(int $mask): void
    {
        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                if ($this->fn[$y][$x]) {
                    continue;
                }
                switch ($mask) {
                    case 0: $inv = ($x + $y) % 2 === 0; break;
                    case 1: $inv = $y % 2 === 0; break;
                    case 2: $inv = $x % 3 === 0; break;
                    case 3: $inv = ($x + $y) % 3 === 0; break;
                    case 4: $inv = (intdiv($x, 3) + intdiv($y, 2)) % 2 === 0; break;
                    case 5: $inv = ($x * $y) % 2 + ($x * $y) % 3 === 0; break;
                    case 6: $inv = (($x * $y) % 2 + ($x * $y) % 3) % 2 === 0; break;
                    default: $inv = ((($x + $y) % 2) + ($x * $y) % 3) % 2 === 0; break;
                }
                if ($inv) {
                    $this->m[$y][$x] ^= 1;
                }
            }
        }
    }

    private function drawFormatBits(int $mask): void
    {
        $size = $this->size;
        $data = $mask; // nível M => formatBits = 0
        $rem = $data;
        for ($i = 0; $i < 10; $i++) {
            $rem = ($rem << 1) ^ (($rem >> 9) * 0x537);
        }
        $bits = (($data << 10) | $rem) ^ 0x5412;

        for ($i = 0; $i <= 5; $i++) {
            $this->m[$i][8] = ($bits >> $i) & 1;
        }
        $this->m[7][8] = ($bits >> 6) & 1;
        $this->m[8][8] = ($bits >> 7) & 1;
        $this->m[8][7] = ($bits >> 8) & 1;
        for ($i = 9; $i < 15; $i++) {
            $this->m[8][14 - $i] = ($bits >> $i) & 1;
        }
        // Segunda cópia: bits 0-6 na vertical (col 8), bits 7-14 na horizontal (linha 8)
        for ($i = 0; $i < 7; $i++) {
            $this->m[$size - 1 - $i][8] = ($bits >> $i) & 1;
        }
        for ($i = 7; $i < 15; $i++) {
            $this->m[8][$size - 15 + $i] = ($bits >> $i) & 1;
        }
        $this->m[$size - 8][8] = 1; // módulo escuro fixo
    }

    // ─── Penalidade (escolha de máscara) ────────────────────────────────

    private function penaltyScore(): int
    {
        $size = $this->size;
        $m = $this->m;
        $score = 0;

        // Regra 1: corridas de mesma cor
        for ($y = 0; $y < $size; $y++) {
            $run = 1;
            for ($x = 1; $x < $size; $x++) {
                if ($m[$y][$x] === $m[$y][$x - 1]) {
                    $run++;
                } else {
                    if ($run >= 5) {
                        $score += 3 + ($run - 5);
                    }
                    $run = 1;
                }
            }
            if ($run >= 5) {
                $score += 3 + ($run - 5);
            }
        }
        for ($x = 0; $x < $size; $x++) {
            $run = 1;
            for ($y = 1; $y < $size; $y++) {
                if ($m[$y][$x] === $m[$y - 1][$x]) {
                    $run++;
                } else {
                    if ($run >= 5) {
                        $score += 3 + ($run - 5);
                    }
                    $run = 1;
                }
            }
            if ($run >= 5) {
                $score += 3 + ($run - 5);
            }
        }

        // Regra 2: blocos 2x2
        for ($y = 0; $y < $size - 1; $y++) {
            for ($x = 0; $x < $size - 1; $x++) {
                $c = $m[$y][$x];
                if ($c === $m[$y][$x + 1] && $c === $m[$y + 1][$x] && $c === $m[$y + 1][$x + 1]) {
                    $score += 3;
                }
            }
        }

        // Regra 3: padrão 1:1:3:1:1 com zona clara
        $patterns = [[1,0,1,1,1,0,1,0,0,0,0], [0,0,0,0,1,0,1,1,1,0,1]];
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x <= $size - 11; $x++) {
                foreach ($patterns as $pat) {
                    $ok = true;
                    for ($k = 0; $k < 11; $k++) {
                        if ($m[$y][$x + $k] !== $pat[$k]) { $ok = false; break; }
                    }
                    if ($ok) { $score += 40; }
                }
            }
        }
        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y <= $size - 11; $y++) {
                foreach ($patterns as $pat) {
                    $ok = true;
                    for ($k = 0; $k < 11; $k++) {
                        if ($m[$y + $k][$x] !== $pat[$k]) { $ok = false; break; }
                    }
                    if ($ok) { $score += 40; }
                }
            }
        }

        // Regra 4: proporção de módulos escuros
        $dark = 0;
        for ($y = 0; $y < $size; $y++) {
            $dark += array_sum($m[$y]);
        }
        $total = $size * $size;
        $k = (int) (abs($dark * 20 - $total * 10) / $total);
        $score += $k * 10;

        return $score;
    }

    // ─── Saída PNG (grayscale, sem GD) ──────────────────────────────────

    private function toPng(int $scale, int $margin): string
    {
        $n = $this->size;
        $dim = ($n + 2 * $margin) * $scale;

        $raw = '';
        for ($y = 0; $y < $dim; $y++) {
            $raw .= "\x00"; // filtro "none"
            $my = intdiv($y, $scale) - $margin;
            for ($x = 0; $x < $dim; $x++) {
                $mx = intdiv($x, $scale) - $margin;
                $dark = $my >= 0 && $my < $n && $mx >= 0 && $mx < $n && $this->m[$my][$mx] === 1;
                $raw .= $dark ? "\x00" : "\xFF";
            }
        }

        $chunk = static function (string $type, string $data): string {
            return pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        };

        $ihdr = pack('N', $dim) . pack('N', $dim) . "\x08\x00\x00\x00\x00"; // 8-bit grayscale
        return "\x89PNG\r\n\x1a\n"
            . $chunk('IHDR', $ihdr)
            . $chunk('IDAT', gzcompress($raw, 9))
            . $chunk('IEND', '');
    }
}
