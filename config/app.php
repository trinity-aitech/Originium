<?php

declare(strict_types=1);

use App\Core\Env;

return [
    'name'     => Env::get('APP_NAME', 'Originium'),
    'env'      => Env::get('APP_ENV', 'production'),
    'debug'    => filter_var(Env::get('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),
    'url'      => Env::get('APP_URL', ''),
    'timezone' => 'America/Sao_Paulo',
];
