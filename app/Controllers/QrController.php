<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\QrCode;

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

    /** Gera/serve o PNG do QR Code do perfil (gerado localmente em PHP puro). */
    public function png(): void
    {
        $user = Auth::user();
        $target = $this->absoluteProfileUrl($user['username']);

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
        header('Cache-Control: private, max-age=86400');
        if (isset($_GET['download'])) {
            header('Content-Disposition: attachment; filename="originium-' . $user['username'] . '.png"');
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
