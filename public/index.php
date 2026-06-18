<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Originium — Front Controller
|--------------------------------------------------------------------------
| Toda requisição passa por aqui. Carrega o ambiente, registra o autoloader
| PSR-4 próprio (sem Composer) e entrega o controle ao Router.
*/

/*
 | BASE_DIR aponta para a raiz do projeto (onde ficam app/, config/, ...).
 | - Local (XAMPP): este index.php está em /public, então a raiz é a pasta acima.
 | - Hospedagem sem docroot configurável (ex.: InfinityFree): copie o conteúdo
 |   de /public para a raiz pública (htdocs) junto com app/, config/, etc.;
 |   aqui detectamos isso automaticamente.
 */
define('BASE_DIR', is_dir(__DIR__ . '/app') ? __DIR__ : dirname(__DIR__));

// Raiz pública (onde está este index.php e a pasta assets/). Vale tanto local
// (/public) quanto em hospedagem "flat" (htdocs) — usado para salvar uploads
// exatamente onde eles são servidos pela web.
define('PUBLIC_DIR', __DIR__);

require BASE_DIR . '/app/Core/helpers.php';

spl_autoload_register(static function (string $class): void {
    if (strncmp($class, 'App\\', 4) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, 4));
    $file = BASE_DIR . '/app/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

use App\Core\Env;
use App\Core\Router;
use App\Core\Session;

Env::load(BASE_DIR . '/.env');

date_default_timezone_set(config('app.timezone', 'UTC'));

if (config('app.debug', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', BASE_DIR . '/storage/logs/php-error.log');
}

Session::start();

$router = new Router();
$router->load(require BASE_DIR . '/config/routes.php');

try {
    $router->dispatch(
        $_SERVER['REQUEST_METHOD'] ?? 'GET',
        $_SERVER['REQUEST_URI'] ?? '/'
    );
} catch (\Throwable $e) {
    error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    if (config('app.debug', false)) {
        echo '<pre style="padding:2rem;font:14px monospace;background:#0a0a0b;color:#f87171">';
        echo e((string) $e);
        echo '</pre>';
    } else {
        require BASE_DIR . '/app/Views/errors/500.php';
    }
}
