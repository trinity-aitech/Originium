<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Theme;
use App\Models\User;

final class AuthController extends Controller
{
    public function showRegister(): void
    {
        $this->view('auth/register', ['title' => 'Criar conta'], 'auth');
    }

    public function register(): void
    {
        $username = strtolower(trim($_POST['username'] ?? ''));
        $email    = strtolower(trim($_POST['email'] ?? ''));

        $v = new Validator($_POST);
        $v->required('username', 'Usuário')->min('username', 3, 'Usuário')->max('username', 30, 'Usuário')
          ->regex('username', '/^[a-z0-9_]+$/', 'Usuário deve conter apenas letras minúsculas, números e _.')
          ->required('display_name', 'Nome')->max('display_name', 60, 'Nome')
          ->required('email', 'E-mail')->email('email', 'E-mail')
          ->required('password', 'Senha')->min('password', 8, 'Senha')
          ->matches('password_confirmation', 'password', 'As senhas não coincidem.')
          ->check(!User::usernameExists($username), 'username', 'Este usuário já está em uso.')
          ->check(!User::emailExists($email), 'email', 'Este e-mail já está cadastrado.');

        $this->validate($v, 'register');

        $theme = Theme::default();
        $id = User::create([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'display_name'  => trim($_POST['display_name']),
            'theme_id'      => $theme['id'] ?? null,
        ]);

        Auth::login(User::find($id));
        Session::flash('success', 'Conta criada com sucesso. Bem-vindo ao Originium!');
        redirect('dashboard');
    }

    public function showLogin(): void
    {
        $this->view('auth/login', ['title' => 'Entrar'], 'auth');
    }

    public function login(): void
    {
        $v = new Validator($_POST);
        $v->required('email', 'E-mail')->required('password', 'Senha');
        $this->validate($v, 'login');

        $email = strtolower(trim($_POST['email']));
        if (!Auth::attempt($email, $_POST['password'])) {
            Session::flash('errors', ['email' => ['E-mail ou senha incorretos.']]);
            Session::flash('old', ['email' => $email]);
            redirect('login');
        }

        Session::flash('success', 'Bem-vindo de volta!');
        redirect('dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }
}
