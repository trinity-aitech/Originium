<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Click;
use App\Models\ContactMessage;
use App\Models\Link;
use App\Models\Testimonial;
use App\Models\User;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $user  = Auth::user();
        $links = Link::forUser((int) $user['id']);

        $stats = [
            'links'  => count($links),
            'active' => count(array_filter($links, static fn ($l) => (int) $l['is_active'] === 1)),
            'clicks' => Click::totalClicks((int) $user['id']),
            'views'  => Click::totalViews((int) $user['id']),
        ];

        $checklist = $this->buildChecklist($user, count($links));

        $this->view('dashboard/index', [
            'title'     => 'Visão geral',
            'user'      => $user,
            'links'     => $links,
            'stats'     => $stats,
            'checklist' => $checklist,
            'unread'    => ContactMessage::unreadCount((int) $user['id']),
        ]);
    }

    /** Checklist de conclusão do perfil. */
    private function buildChecklist(array $user, int $linkCount): array
    {
        $uid = (int) $user['id'];
        $items = [
            ['Adicionar foto de perfil',  !empty($user['avatar_path']),         'dashboard/profile'],
            ['Escrever uma bio',          !empty($user['bio']),                 'dashboard/profile'],
            ['Definir cabeçalho',         !empty($user['headline']),            'dashboard/blueprint'],
            ['Criar o primeiro link',     $linkCount > 0,                       'dashboard/links'],
            ['Preencher o blueprint',     !empty($user['bp_values']) || !empty($user['bp_work_method']), 'dashboard/blueprint'],
            ['Adicionar um depoimento',   Testimonial::countForUser($uid) > 0,  'dashboard/testimonials'],
        ];
        $done = count(array_filter($items, static fn ($i) => $i[1]));
        return [
            'items'   => $items,
            'done'    => $done,
            'total'   => count($items),
            'percent' => (int) round($done / max(1, count($items)) * 100),
        ];
    }

    public function editProfile(): void
    {
        $this->view('dashboard/profile', [
            'title' => 'Editar perfil',
            'user'  => Auth::user(),
        ]);
    }

    public function updateProfile(): void
    {
        $user = Auth::user();
        $username = strtolower(trim($_POST['username'] ?? ''));

        $v = new Validator($_POST);
        $v->required('username', 'Usuário')->min('username', 3, 'Usuário')->max('username', 30, 'Usuário')
          ->regex('username', '/^[a-z0-9_]+$/', 'Usuário deve conter apenas letras minúsculas, números e _.')
          ->required('display_name', 'Nome')->max('display_name', 60, 'Nome')
          ->max('bio', 160, 'Bio')
          ->check(!User::usernameExists($username, (int) $user['id']), 'username', 'Este usuário já está em uso.');

        $this->validate($v, 'dashboard/profile');

        $avatar = $user['avatar_path'];
        $file = Request::file('avatar');
        if ($file && (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $stored = $this->storeAvatar($file);
            if ($stored !== null) {
                $avatar = $stored;
            }
        }

        User::updateProfile((int) $user['id'], [
            'username'     => $username,
            'display_name' => trim($_POST['display_name']),
            'bio'          => trim($_POST['bio'] ?? ''),
            'avatar_path'  => $avatar,
        ]);

        Session::flash('success', 'Perfil atualizado.');
        redirect('dashboard/profile');
    }

    /** Valida e guarda o avatar enviado. Retorna o caminho relativo ou null. */
    private function storeAvatar(array $file): ?string
    {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            Session::flash('error', 'A imagem deve ter no máximo 2MB.');
            return null;
        }
        $mime = function_exists('mime_content_type') ? mime_content_type($file['tmp_name']) : null;
        if (!isset($allowed[$mime])) {
            Session::flash('error', 'Formato de imagem inválido (use JPG, PNG ou WEBP).');
            return null;
        }

        $name = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        $dir  = BASE_DIR . '/public/assets/uploads';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            return null;
        }
        return 'uploads/' . $name;
    }
}
