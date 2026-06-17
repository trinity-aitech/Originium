<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\User;

final class BlueprintController extends Controller
{
    public function edit(): void
    {
        $this->view('blueprint/edit', [
            'title' => 'Blueprint profissional',
            'user'  => Auth::user(),
        ]);
    }

    public function update(): void
    {
        $v = (new Validator($_POST))
            ->max('headline', 120, 'Cabeçalho')
            ->max('availability', 120, 'Disponibilidade')
            ->max('working_hours', 120, 'Horário')
            ->max('current_focus', 200, 'Foco atual')
            ->max('project_status', 40, 'Status')
            ->max('values', 1000, 'Valores')
            ->max('work_method', 1000, 'Método de trabalho')
            ->max('contact_prefs', 600, 'Preferências de contato')
            ->max('client_compat', 1000, 'Compatibilidade')
            ->max('expectations', 1000, 'Expectativas');
        $this->validate($v, 'dashboard/blueprint');

        $id = (int) Auth::id();

        // headline fica em users.headline; o resto nas colunas bp_*
        User::updateHeadline($id, [
            'headline'       => trim($_POST['headline'] ?? ''),
            'current_focus'  => trim($_POST['current_focus'] ?? ''),
            'project_status' => trim($_POST['project_status'] ?? ''),
        ]);
        User::updateBlueprint($id, [
            'values'         => trim($_POST['values'] ?? ''),
            'work_method'    => trim($_POST['work_method'] ?? ''),
            'availability'   => trim($_POST['availability'] ?? ''),
            'working_hours'  => trim($_POST['working_hours'] ?? ''),
            'contact_prefs'  => trim($_POST['contact_prefs'] ?? ''),
            'current_focus'  => trim($_POST['current_focus'] ?? ''),
            'project_status' => trim($_POST['project_status'] ?? ''),
            'client_compat'  => trim($_POST['client_compat'] ?? ''),
            'expectations'   => trim($_POST['expectations'] ?? ''),
        ]);

        Session::flash('success', 'Blueprint atualizado.');
        redirect('dashboard/blueprint');
    }
}
