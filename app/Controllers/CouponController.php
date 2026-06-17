<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ResourceController;
use App\Core\Validator;
use App\Models\Coupon;

final class CouponController extends ResourceController
{
    protected string $model = Coupon::class;
    protected string $route = 'dashboard/coupons';
    protected string $viewPath = 'coupons/index';
    protected string $title = 'Cupons';
    protected bool $hasToggle = true;

    protected function makeValidator(): Validator
    {
        return (new Validator($_POST))
            ->required('code', 'Código')->max('code', 40, 'Código')
            ->max('description', 160, 'Descrição')
            ->max('discount_label', 40, 'Desconto')
            ->url('url', 'Link');
    }

    protected function input(): array
    {
        $expires = trim($_POST['expires_at'] ?? '');
        return [
            'code'           => strtoupper(trim($_POST['code'])),
            'description'    => trim($_POST['description'] ?? ''),
            'discount_label' => trim($_POST['discount_label'] ?? ''),
            'url'            => trim($_POST['url'] ?? '') ?: null,
            'expires_at'     => $expires !== '' ? $expires : null,
            'is_active'      => isset($_POST['is_active']) ? 1 : 0,
        ];
    }
}
