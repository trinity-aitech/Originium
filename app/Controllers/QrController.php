<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

final class QrController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        $this->view('qr/index', [
            'title'      => 'QR Code',
            'user'       => $user,
            'profileUrl' => $this->absoluteProfileUrl($user['username']),
        ]);
    }

    /** Gera/serve o PNG do QR Code do perfil. */
    public function png(): void
    {
        $user = Auth::user();
        $target = $this->absoluteProfileUrl($user['username']);
        $endpoint = 'https://api.qrserver.com/v1/create-qr-code/?size=600x600&margin=16&qzone=1&format=png&data=' . urlencode($target);

        $png = $this->fetchBinary($endpoint);
        if ($png === null) {
            http_response_code(502);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Não foi possível gerar o QR agora. Verifique a conexão do servidor.';
            return;
        }

        header('Content-Type: image/png');
        header('Cache-Control: private, max-age=86400');
        if (isset($_GET['download'])) {
            header('Content-Disposition: attachment; filename="originium-' . $user['username'] . '.png"');
        }
        echo $png;
    }

    private function absoluteProfileUrl(string $username): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . url('u/' . $username);
    }

    private function fetchBinary(string $url): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 8,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            $data = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($data !== false && $code === 200) {
                return $data;
            }
        }
        if (ini_get('allow_url_fopen')) {
            $ctx = stream_context_create(['http' => ['timeout' => 8]]);
            $data = @file_get_contents($url, false, $ctx);
            if ($data !== false) {
                return $data;
            }
        }
        return null;
    }
}
