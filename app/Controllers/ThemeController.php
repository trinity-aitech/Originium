<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Theme;
use App\Models\User;

final class ThemeController extends Controller
{
    public function index(): void
    {
        $this->view('themes/index', [
            'title'  => 'Temas',
            'themes' => Theme::all(),
            'user'   => Auth::user(),
        ]);
    }

    public function update(): void
    {
        $themeId = (int) ($_POST['theme_id'] ?? 0);
        if (Theme::find($themeId)) {
            User::updateTheme((int) Auth::id(), $themeId);
            Session::flash('success', 'Tema aplicado ao seu perfil.');
        } else {
            Session::flash('error', 'Tema inválido.');
        }
        redirect('dashboard/themes');
    }
}
