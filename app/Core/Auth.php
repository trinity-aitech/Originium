<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

/** Autenticação baseada em sessão. */
final class Auth
{
    private static ?array $cached = null;

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            self::login($user);
            return true;
        }
        return false;
    }

    public static function login(array $user): void
    {
        Session::regenerate();
        Session::set('user_id', (int) $user['id']);
        self::$cached = $user;
    }

    public static function logout(): void
    {
        Session::forget('user_id');
        self::$cached = null;
        Session::regenerate();
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function id(): ?int
    {
        $id = Session::get('user_id');
        return $id !== null ? (int) $id : null;
    }

    public static function user(): ?array
    {
        if (self::$cached !== null) {
            return self::$cached;
        }
        $id = self::id();
        if ($id === null) {
            return null;
        }
        return self::$cached = User::find($id);
    }
}
