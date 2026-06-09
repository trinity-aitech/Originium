<?php

declare(strict_types=1);

namespace App\Core;

/** Renderiza templates PHP dentro de um layout opcional. */
final class View
{
    public static function render(string $template, array $data = [], ?string $layout = 'app'): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require BASE_DIR . '/app/Views/' . $template . '.php';
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        require BASE_DIR . '/app/Views/layouts/' . $layout . '.php';
    }
}
