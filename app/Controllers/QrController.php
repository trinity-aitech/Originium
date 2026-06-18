<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\QrCode;
use App\Core\Session;
use App\Models\User;

final class QrController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        $this->view('qr/index', [
            'title'      => 'QR Code',
            'user'       => $user,
            'profileUrl' => $this->absoluteProfileUrl($user['username']),
        ]);
    }

    /** Liga/desliga a exibição do QR no perfil público. */
    public function toggleProfile(): void
    {
        $user = Auth::user();
        User::setShowQr((int) $user['id'], (int) $user['show_qr'] === 1 ? 0 : 1);
        Session::flash('success', 'Preferência do QR Code atualizada.');
        redirect('dashboard/qr');
    }

    /** PNG do QR do próprio usuário (dashboard, requer login). */
    public function png(): void
    {
        $user = Auth::user();
        $this->streamQr($this->absoluteProfileUrl($user['username']), $user['username'], isset($_GET['download']));
    }

    /** PNG público do QR de um perfil (se o dono ativou a exibição). */
    public function publicPng(string $username): void
    {
        $user = User::findByUsername(strtolower($username));
        if (!$user || (int) $user['is_active'] !== 1 || (int) $user['show_qr'] !== 1) {
            $this->notFound();
        }
        $this->streamQr($this->absoluteProfileUrl($user['username']), $user['username'], false);
    }

    private function streamQr(string $target, string $username, bool $download): void
    {
        try {
            $png = QrCode::png($target, 10, 4);
        } catch (\Throwable $e) {
            error_log('QR: ' . $e->getMessage());
            http_response_code(500);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Não foi possível gerar o QR.';
            return;
        }

        header('Content-Type: image/png');
        header('Cache-Control: public, max-age=86400');
        if ($download) {
            header('Content-Disposition: attachment; filename="originium-' . $username . '.png"');
        }
        echo $png;
    }

    private function absoluteProfileUrl(string $username): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . url('u/' . $username);
    }
}
