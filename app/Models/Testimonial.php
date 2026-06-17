<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\OrderedModel;

final class Testimonial extends OrderedModel
{
    protected static string $table = 'testimonials';

    public static function create(int $userId, array $d): int
    {
        $stmt = self::pdo()->prepare(
            'INSERT INTO testimonials (user_id, author_name, author_role, quote, position, is_active)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $userId, $d['author_name'], $d['author_role'], $d['quote'],
            self::nextPosition($userId), $d['is_active'],
        ]);
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(int $id, int $userId, array $d): void
    {
        $stmt = self::pdo()->prepare(
            'UPDATE testimonials SET author_name = ?, author_role = ?, quote = ?, is_active = ?
              WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$d['author_name'], $d['author_role'], $d['quote'], $d['is_active'], $id, $userId]);
    }
}
