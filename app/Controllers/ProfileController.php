<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Click;
use App\Models\Link;
use App\Models\Theme;
use App\Models\User;

final class ProfileController extends Controller
{
    public function show(string $username): void
    {
        $user = User::findByUsername(strtolower($username));
        if (!$user || (int) $user['is_active'] !== 1) {
            $this->notFound();
        }

        $theme = $user['theme_id'] ? Theme::find((int) $user['theme_id']) : null;
        $theme = $theme ?: Theme::default();

        Click::recordProfileView((int) $user['id'], hash('sha256', Request::ip()));

        $this->view('profile/show', [
            'title' => ($user['display_name'] ?: $user['username']) . ' — Originium',
            'user'  => $user,
            'theme' => $theme,
            'links' => Link::forUser((int) $user['id'], true),
        ], null);
    }
}
