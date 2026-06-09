<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Theme
{
    public static function all(): array
    {
        return Database::pdo()->query('SELECT * FROM themes ORDER BY id ASC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM themes WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function default(): ?array
    {
        $row = Database::pdo()->query('SELECT * FROM themes WHERE is_default = 1 LIMIT 1')->fetch();
        if ($row) {
            return $row;
        }
        return Database::pdo()->query('SELECT * FROM themes ORDER BY id ASC LIMIT 1')->fetch() ?: null;
    }
}
