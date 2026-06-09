<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Click;
use App\Models\Link;

final class RedirectController extends Controller
{
    public function go(string $id): void
    {
        $link = Link::find((int) $id);
        if (!$link || (int) $link['is_active'] !== 1) {
            $this->notFound();
        }

        Click::record((int) $link['id'], (int) $link['user_id'], [
            'referrer'   => Request::referer(),
            'user_agent' => Request::userAgent(),
            'ip_hash'    => hash('sha256', Request::ip()),
        ]);
        Link::incrementClicks((int) $link['id']);

        header('Location: ' . $link['url']);
        exit;
    }
}
