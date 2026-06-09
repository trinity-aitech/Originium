<?php

declare(strict_types=1);

namespace App\Core;

/** Controller base com helpers de view, json e validação. */
abstract class Controller
{
    protected function view(string $template, array $data = [], ?string $layout = 'app'): void
    {
        View::render($template, $data, $layout);
    }

    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /** Se a validação falhar, guarda erros + input e volta para a rota. */
    protected function validate(Validator $validator, string $redirectTo): void
    {
        if ($validator->fails()) {
            Session::flash('errors', $validator->errors());
            Session::flash('old', $_POST);
            redirect($redirectTo);
        }
    }

    protected function notFound(): void
    {
        http_response_code(404);
        require BASE_DIR . '/app/Views/errors/404.php';
        exit;
    }
}
