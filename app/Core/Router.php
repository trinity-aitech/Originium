<?php

declare(strict_types=1);

namespace App\Core;

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\GuestMiddleware;

/** Roteador simples com parâmetros dinâmicos e middlewares. */
final class Router
{
    private array $routes = [];

    private const MIDDLEWARE = [
        'auth'  => AuthMiddleware::class,
        'guest' => GuestMiddleware::class,
        'csrf'  => CsrfMiddleware::class,
    ];

    public function load(array $routes): void
    {
        foreach ($routes as $route) {
            $this->routes[] = [
                'method'     => strtoupper($route[0]),
                'pattern'    => $route[1],
                'action'     => $route[2],
                'middleware' => $route[3] ?? [],
            ];
        }
    }

    public function dispatch(string $method, string $requestUri): void
    {
        $method = strtoupper($method);
        $path = $this->normalize($requestUri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $params = $this->match($route['pattern'], $path);
            if ($params === null) {
                continue;
            }

            foreach ($route['middleware'] as $name) {
                if (isset(self::MIDDLEWARE[$name])) {
                    (new (self::MIDDLEWARE[$name])())->handle();
                }
            }

            [$controller, $action] = explode('@', $route['action']);
            $class = 'App\\Controllers\\' . $controller;
            (new $class())->{$action}(...array_values($params));
            return;
        }

        http_response_code(404);
        require BASE_DIR . '/app/Views/errors/404.php';
    }

    /** Remove a subpasta base e normaliza o caminho da rota. */
    private function normalize(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rawurldecode($path ?: '/');

        $base = base_path();
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        $path = preg_replace('#^/index\.php#', '', $path) ?? $path;
        $path = '/' . trim($path, '/');

        return $path === '' ? '/' : $path;
    }

    /** Casa o padrão da rota e devolve os parâmetros nomeados, ou null. */
    private function match(string $pattern, string $path): ?array
    {
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        if (!preg_match('#^' . $regex . '$#', $path, $matches)) {
            return null;
        }
        $params = [];
        foreach ($matches as $key => $value) {
            if (!is_int($key)) {
                $params[$key] = $value;
            }
        }
        return $params;
    }
}
