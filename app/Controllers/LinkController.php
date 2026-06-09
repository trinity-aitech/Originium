<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Link;

final class LinkController extends Controller
{
    public function index(): void
    {
        $this->view('links/index', [
            'title' => 'Meus links',
            'links' => Link::forUser((int) Auth::id()),
        ]);
    }

    public function create(): void
    {
        $this->view('links/form', [
            'title' => 'Novo link',
            'link'  => null,
        ]);
    }

    public function store(): void
    {
        $v = $this->validateLink();
        $this->validate($v, 'dashboard/links/create');

        Link::create((int) Auth::id(), [
            'title'     => trim($_POST['title']),
            'url'       => trim($_POST['url']),
            'icon'      => trim($_POST['icon'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);

        Session::flash('success', 'Link adicionado.');
        redirect('dashboard/links');
    }

    public function edit(string $id): void
    {
        $link = Link::findOwned((int) $id, (int) Auth::id());
        if (!$link) {
            $this->notFound();
        }
        $this->view('links/form', [
            'title' => 'Editar link',
            'link'  => $link,
        ]);
    }

    public function update(string $id): void
    {
        $link = Link::findOwned((int) $id, (int) Auth::id());
        if (!$link) {
            $this->notFound();
        }

        $v = $this->validateLink();
        $this->validate($v, 'dashboard/links/' . (int) $id . '/edit');

        Link::update((int) $id, (int) Auth::id(), [
            'title'     => trim($_POST['title']),
            'url'       => trim($_POST['url']),
            'icon'      => trim($_POST['icon'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);

        Session::flash('success', 'Link atualizado.');
        redirect('dashboard/links');
    }

    public function destroy(string $id): void
    {
        Link::delete((int) $id, (int) Auth::id());
        Session::flash('success', 'Link removido.');
        redirect('dashboard/links');
    }

    public function toggle(string $id): void
    {
        Link::toggle((int) $id, (int) Auth::id());
        redirect('dashboard/links');
    }

    public function move(string $id): void
    {
        $direction = ($_POST['direction'] ?? '') === 'up' ? 'up' : 'down';
        Link::move((int) $id, (int) Auth::id(), $direction);
        redirect('dashboard/links');
    }

    private function validateLink(): Validator
    {
        $v = new Validator($_POST);
        $v->required('title', 'Título')->max('title', 80, 'Título')
          ->required('url', 'URL')->url('url', 'URL')->max('url', 2048, 'URL')
          ->max('icon', 40, 'Ícone');
        return $v;
    }
}
