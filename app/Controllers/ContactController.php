<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Models\ContactField;
use App\Models\ContactMessage;
use App\Models\User;

final class ContactController extends Controller
{
    private const ROUTE = 'dashboard/contact';

    public function index(): void
    {
        $user = Auth::user();
        $this->view('contact/index', [
            'title'    => 'Formulário de contato',
            'user'     => $user,
            'fields'   => ContactField::forUser((int) $user['id']),
            'messages' => ContactMessage::forUser((int) $user['id']),
            'types'    => ContactField::TYPES,
            'editing'  => isset($_GET['edit']) ? ContactField::findOwned((int) $_GET['edit'], (int) $user['id']) : null,
        ]);
        ContactMessage::markAllRead((int) $user['id']);
    }

    public function toggleEnabled(): void
    {
        $user = Auth::user();
        User::setContactEnabled((int) $user['id'], (int) $user['contact_enabled'] === 1 ? 0 : 1);
        Session::flash('success', 'Preferência do formulário atualizada.');
        redirect(self::ROUTE);
    }

    public function storeField(): void
    {
        $this->validateField();
        ContactField::create((int) Auth::id(), $this->fieldInput());
        Session::flash('success', 'Campo adicionado.');
        redirect(self::ROUTE);
    }

    public function updateField(string $id): void
    {
        if (!ContactField::findOwned((int) $id, (int) Auth::id())) {
            $this->notFound();
        }
        $this->validateField(self::ROUTE . '?edit=' . (int) $id);
        ContactField::update((int) $id, (int) Auth::id(), $this->fieldInput());
        Session::flash('success', 'Campo atualizado.');
        redirect(self::ROUTE);
    }

    public function destroyField(string $id): void
    {
        ContactField::delete((int) $id, (int) Auth::id());
        Session::flash('success', 'Campo removido.');
        redirect(self::ROUTE);
    }

    public function moveField(string $id): void
    {
        $dir = ($_POST['direction'] ?? '') === 'up' ? 'up' : 'down';
        ContactField::move((int) $id, (int) Auth::id(), $dir);
        redirect(self::ROUTE);
    }

    public function deleteMessage(string $id): void
    {
        ContactMessage::delete((int) $id, (int) Auth::id());
        Session::flash('success', 'Mensagem apagada.');
        redirect(self::ROUTE);
    }

    /** Recebe o envio do formulário público (/u/{username}/contact). */
    public function submit(string $username): void
    {
        $user = User::findByUsername(strtolower($username));
        if (!$user || (int) $user['contact_enabled'] !== 1) {
            $this->notFound();
        }
        $fields = ContactField::forUser((int) $user['id']);

        $payload = [];
        foreach ($fields as $field) {
            $value = trim((string) Request::post('field_' . $field['id'], ''));
            if ((int) $field['is_required'] === 1 && $value === '') {
                redirect('u/' . $user['username'] . '?contact=error');
            }
            if ($value !== '') {
                $payload[$field['label']] = mb_substr($value, 0, 2000);
            }
        }

        $ipHash = hash('sha256', Request::ip());
        if (ContactMessage::recentCount((int) $user['id'], $ipHash) >= 5) {
            redirect('u/' . $user['username'] . '?contact=limit');
        }

        ContactMessage::create((int) $user['id'], $payload, $ipHash);
        redirect('u/' . $user['username'] . '?contact=sent');
    }

    private function validateField(?string $back = null): void
    {
        $v = (new Validator($_POST))
            ->required('label', 'Rótulo')->max('label', 80, 'Rótulo')
            ->max('placeholder', 120, 'Placeholder')
            ->check(array_key_exists((string) Request::post('field_type'), ContactField::TYPES), 'field_type', 'Tipo inválido.');
        $this->validate($v, $back ?? self::ROUTE);
    }

    private function fieldInput(): array
    {
        return [
            'label'       => trim($_POST['label']),
            'field_type'  => (string) Request::post('field_type', 'text'),
            'placeholder' => trim($_POST['placeholder'] ?? ''),
            'is_required' => isset($_POST['is_required']) ? 1 : 0,
        ];
    }
}
