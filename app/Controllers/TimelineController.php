<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ResourceController;
use App\Core\Validator;
use App\Models\TimelineEvent;

final class TimelineController extends ResourceController
{
    protected string $model = TimelineEvent::class;
    protected string $route = 'dashboard/timeline';
    protected string $viewPath = 'timeline/index';
    protected string $title = 'Linha do tempo';

    protected function makeValidator(): Validator
    {
        return (new Validator($_POST))
            ->required('period', 'Período')->max('period', 40, 'Período')
            ->required('title', 'Título')->max('title', 120, 'Título')
            ->max('description', 600, 'Descrição');
    }

    protected function input(): array
    {
        return [
            'period'      => trim($_POST['period']),
            'title'       => trim($_POST['title']),
            'description' => trim($_POST['description'] ?? ''),
        ];
    }
}
