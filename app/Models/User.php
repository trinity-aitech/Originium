<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class User
{
    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public static function findByUsername(string $username): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    public static function emailExists(string $email): bool
    {
        return self::findByEmail($email) !== null;
    }

    public static function usernameExists(string $username, ?int $exceptId = null): bool
    {
        $sql = 'SELECT id FROM users WHERE username = ?';
        $params = [$username];
        if ($exceptId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $exceptId;
        }
        $stmt = Database::pdo()->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public static function create(array $d): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO users (username, email, password_hash, display_name, theme_id, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())'
        );
        $stmt->execute([
            $d['username'],
            $d['email'],
            $d['password_hash'],
            $d['display_name'],
            $d['theme_id'],
        ]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function updateProfile(int $id, array $d): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE users
                SET username = ?, display_name = ?, bio = ?, avatar_path = ?, updated_at = NOW()
              WHERE id = ?'
        );
        $stmt->execute([
            $d['username'],
            $d['display_name'],
            $d['bio'],
            $d['avatar_path'],
            $id,
        ]);
    }

    public static function updateTheme(int $id, int $themeId): void
    {
        $stmt = Database::pdo()->prepare('UPDATE users SET theme_id = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$themeId, $id]);
    }
}
