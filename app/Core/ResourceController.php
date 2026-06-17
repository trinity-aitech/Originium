<?php

declare(strict_types=1);

namespace App\Core;

/**
 * CRUD genérico para os editores de conteúdo do dashboard
 * (depoimentos, FAQ, linha do tempo, cupons...). As subclasses só
 * declaram o model, a rota, a view e as regras/campos.
 */
abstract class ResourceController extends Controller
{
    /** @var class-string */
    protected string $model;
    protected string $route;     // ex.: 'dashboard/testimonials'
    protected string $viewPath;  // ex.: 'testimonials/index'
    protected string $title;
    protected bool $hasToggle = false;

    abstract protected function makeValidator(): Validator;

    /** Mapa coluna => valor para create()/update(). */
    abstract protected function input(): array;

    public function index(): void
    {
        $model = $this->model;
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = $model::findOwned((int) $_GET['edit'], (int) Auth::id());
        }
        $this->view($this->viewPath, [
            'title'   => $this->title,
            'items'   => $model::forUser((int) Auth::id()),
            'editing' => $editing,
            'route'   => $this->route,
        ]);
    }

    public function store(): void
    {
        $this->validate($this->makeValidator(), $this->route);
        ($this->model)::create((int) Auth::id(), $this->input());
        Session::flash('success', 'Item adicionado.');
        redirect($this->route);
    }

    public function update(string $id): void
    {
        $model = $this->model;
        if (!$model::findOwned((int) $id, (int) Auth::id())) {
            $this->notFound();
        }
        $this->validate($this->makeValidator(), $this->route . '?edit=' . (int) $id);
        $model::update((int) $id, (int) Auth::id(), $this->input());
        Session::flash('success', 'Item atualizado.');
        redirect($this->route);
    }

    public function destroy(string $id): void
    {
        ($this->model)::delete((int) $id, (int) Auth::id());
        Session::flash('success', 'Item removido.');
        redirect($this->route);
    }

    public function toggle(string $id): void
    {
        ($this->model)::toggle((int) $id, (int) Auth::id());
        redirect($this->route);
    }

    public function move(string $id): void
    {
        $direction = ($_POST['direction'] ?? '') === 'up' ? 'up' : 'down';
        ($this->model)::move((int) $id, (int) Auth::id(), $direction);
        redirect($this->route);
    }
}
