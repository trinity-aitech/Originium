<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ResourceController;
use App\Core\Validator;
use App\Models\ProfileFaq;

final class ProfileFaqController extends ResourceController
{
    protected string $model = ProfileFaq::class;
    protected string $route = 'dashboard/faq';
    protected string $viewPath = 'profilefaq/index';
    protected string $title = 'FAQ do perfil';

    protected function makeValidator(): Validator
    {
        return (new Validator($_POST))
            ->required('question', 'Pergunta')->max('question', 200, 'Pergunta')
            ->required('answer', 'Resposta')->max('answer', 1000, 'Resposta');
    }

    protected function input(): array
    {
        return [
            'question' => trim($_POST['question']),
            'answer'   => trim($_POST['answer']),
        ];
    }
}
