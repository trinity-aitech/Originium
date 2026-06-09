<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;

final class CsrfMiddleware
{
    public function handle(): void
    {
        if (in_array(Request::method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            if (!Csrf::verify($_POST['_token'] ?? null)) {
                http_response_code(419);
                Session::flash('error', 'Sua sessão expirou. Tente novamente.');
                back();
            }
        }
    }
}
