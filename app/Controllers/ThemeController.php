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
    private const ANIMATIONS = ['none', 'aurora', 'orbs', 'gradient'];

    public function index(): void
    {
        $this->view('themes/index', [
            'title'      => 'Temas',
            'themes'     => Theme::all(),
            'user'       => Auth::user(),
            'animations' => self::ANIMATIONS,
        ]);
    }

    public function update(): void
    {
        $themeId = (int) ($_POST['theme_id'] ?? 0);
        if (!Theme::find($themeId)) {
            Session::flash('error', 'Tema inválido.');
            redirect('dashboard/themes');
        }

        $accent = sanitize_hex($_POST['accent_color'] ?? null); // null = usa o do tema
        $animation = in_array($_POST['bg_animation'] ?? 'none', self::ANIMATIONS, true)
            ? $_POST['bg_animation']
            : 'none';

        User::updateAppearance((int) Auth::id(), [
            'theme_id'     => $themeId,
            'accent_color' => $accent,
            'bg_animation' => $animation,
        ]);

        Session::flash('success', 'Aparência atualizada.');
        redirect('dashboard/themes');
    }
}
