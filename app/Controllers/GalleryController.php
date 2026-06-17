<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\GalleryImage;

final class GalleryController extends Controller
{
    public function index(): void
    {
        $this->view('gallery/index', [
            'title' => 'Galeria',
            'items' => GalleryImage::forUser((int) Auth::id()),
            'route' => 'dashboard/gallery',
        ]);
    }

    public function store(): void
    {
        $file = Request::file('image');
        if (!$file || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Selecione uma imagem.');
            redirect('dashboard/gallery');
        }
        $path = $this->storeImage($file);
        if ($path === null) {
            redirect('dashboard/gallery');
        }
        GalleryImage::create((int) Auth::id(), [
            'image_path' => $path,
            'caption'    => trim($_POST['caption'] ?? ''),
        ]);
        Session::flash('success', 'Imagem adicionada.');
        redirect('dashboard/gallery');
    }

    public function destroy(string $id): void
    {
        $img = GalleryImage::findOwned((int) $id, (int) Auth::id());
        if ($img) {
            $full = BASE_DIR . '/public/assets/' . $img['image_path'];
            if (is_file($full)) {
                @unlink($full);
            }
            GalleryImage::delete((int) $id, (int) Auth::id());
            Session::flash('success', 'Imagem removida.');
        }
        redirect('dashboard/gallery');
    }

    public function move(string $id): void
    {
        $direction = ($_POST['direction'] ?? '') === 'up' ? 'up' : 'down';
        GalleryImage::move((int) $id, (int) Auth::id(), $direction);
        redirect('dashboard/gallery');
    }

    private function storeImage(array $file): ?string
    {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (($file['size'] ?? 0) > 4 * 1024 * 1024) {
            Session::flash('error', 'A imagem deve ter no máximo 4MB.');
            return null;
        }
        $mime = function_exists('mime_content_type') ? mime_content_type($file['tmp_name']) : null;
        if (!isset($allowed[$mime])) {
            Session::flash('error', 'Formato inválido (use JPG, PNG ou WEBP).');
            return null;
        }
        $name = 'gallery_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        $dir = BASE_DIR . '/public/assets/uploads';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            return null;
        }
        return 'uploads/' . $name;
    }
}
