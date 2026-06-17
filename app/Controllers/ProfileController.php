<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Click;
use App\Models\Coupon;
use App\Models\ContactField;
use App\Models\GalleryImage;
use App\Models\Link;
use App\Models\ProfileFaq;
use App\Models\Testimonial;
use App\Models\Theme;
use App\Models\TimelineEvent;
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

        $uid = (int) $user['id'];
        $this->view('profile/show', [
            'title'         => ($user['display_name'] ?: $user['username']) . ' — Originium',
            'user'          => $user,
            'theme'         => $theme,
            'palette'       => $this->buildPalette($user, $theme),
            'links'         => Link::forUser($uid, true),
            'testimonials'  => Testimonial::activeForUser($uid),
            'faqs'          => ProfileFaq::forUser($uid),
            'timeline'      => TimelineEvent::forUser($uid),
            'gallery'       => GalleryImage::forUser($uid),
            'coupons'       => Coupon::visibleForUser($uid),
            'contactFields' => (int) $user['contact_enabled'] === 1 ? ContactField::forUser($uid) : [],
        ], null);
    }

    /** Monta as variáveis CSS do tema, com cor de destaque personalizada opcional. */
    private function buildPalette(array $user, array $theme): array
    {
        $accent = sanitize_hex($user['accent_color'] ?? null, $theme['accent'] ?? '#6ea8d8');

        return [
            'bg_from'         => $theme['bg_from'] ?? '#0b1220',
            'bg_to'           => $theme['bg_to'] ?? '#05080f',
            'surface'         => $theme['surface'] ?? 'rgba(255,255,255,0.05)',
            'surface_hover'   => $theme['surface_hover'] ?? 'rgba(255,255,255,0.09)',
            'border'          => $theme['border_color'] ?? 'rgba(255,255,255,0.10)',
            'text'            => $theme['text_color'] ?? '#eef2f8',
            'muted'           => $theme['text_muted'] ?? '#9aa6b8',
            'accent'          => $accent,
            'accent_contrast' => color_contrast($accent),
            'animation'       => preg_replace('/[^a-z]/', '', (string) ($user['bg_animation'] ?? 'none')),
        ];
    }
}
