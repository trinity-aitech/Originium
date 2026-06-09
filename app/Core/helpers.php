<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;

/**
 * Caminho base da aplicação (subpasta onde o index.php roda).
 * Ex.: /Linktree da Temu/public
 */
function base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = str_replace('\\', '/', dirname($script));
    $base = ($dir === '/' || $dir === '.' || $dir === '') ? '' : rtrim($dir, '/');
    return $base;
}

/** Gera uma URL interna respeitando a subpasta da aplicação. */
function url(string $path = ''): string
{
    return base_path() . '/' . ltrim($path, '/');
}

/** URL de um asset em /public/assets. */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/** Escapa saída para HTML (anti-XSS). */
function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/** Redireciona para uma rota interna e encerra. */
function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

/** Volta para a página anterior. */
function back(): void
{
    $ref = $_SERVER['HTTP_REFERER'] ?? url('/');
    header('Location: ' . $ref);
    exit;
}

/** Lê configuração no formato "arquivo.chave". */
function config(string $key, $default = null)
{
    static $cache = [];
    [$file, $name] = array_pad(explode('.', $key, 2), 2, null);
    if (!array_key_exists($file, $cache)) {
        $path = BASE_DIR . '/config/' . $file . '.php';
        $cache[$file] = is_file($path) ? require $path : [];
    }
    if ($name === null) {
        return $cache[$file];
    }
    return $cache[$file][$name] ?? $default;
}

/** Recupera input antigo após falha de validação. */
function old(string $key, $default = '')
{
    $old = Session::getFlash('old', []);
    return $old[$key] ?? $default;
}

/** Lista de erros de validação da última requisição. */
function errors(): array
{
    return Session::getFlash('errors', []);
}

/** Primeira mensagem de erro de um campo. */
function error_for(string $key): ?string
{
    $all = errors();
    if (!isset($all[$key])) {
        return null;
    }
    return is_array($all[$key]) ? ($all[$key][0] ?? null) : $all[$key];
}

function csrf_token(): string
{
    return Csrf::token();
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}
