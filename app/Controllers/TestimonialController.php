<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ResourceController;
use App\Core\Validator;
use App\Models\Testimonial;

final class TestimonialController extends ResourceController
{
    protected string $model = Testimonial::class;
    protected string $route = 'dashboard/testimonials';
    protected string $viewPath = 'testimonials/index';
    protected string $title = 'Depoimentos';
    protected bool $hasToggle = true;

    protected function makeValidator(): Validator
    {
        return (new Validator($_POST))
            ->required('author_name', 'Nome')->max('author_name', 80, 'Nome')
            ->max('author_role', 120, 'Cargo')
            ->required('quote', 'Depoimento')->max('quote', 600, 'Depoimento');
    }

    protected function input(): array
    {
        return [
            'author_name' => trim($_POST['author_name']),
            'author_role' => trim($_POST['author_role'] ?? ''),
            'quote'       => trim($_POST['quote']),
            'is_active'   => isset($_POST['is_active']) ? 1 : 0,
        ];
    }
}
