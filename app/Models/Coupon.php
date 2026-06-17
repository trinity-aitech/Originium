<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\OrderedModel;

final class Coupon extends OrderedModel
{
    protected static string $table = 'coupons';

    public static function create(int $userId, array $d): int
    {
        $stmt = self::pdo()->prepare(
            'INSERT INTO coupons (user_id, code, description, discount_label, url, expires_at, is_active, position)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $userId, $d['code'], $d['description'], $d['discount_label'], $d['url'],
            $d['expires_at'], $d['is_active'], self::nextPosition($userId),
        ]);
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(int $id, int $userId, array $d): void
    {
        $stmt = self::pdo()->prepare(
            'UPDATE coupons SET code = ?, description = ?, discount_label = ?, url = ?, expires_at = ?, is_active = ?
              WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([
            $d['code'], $d['description'], $d['discount_label'], $d['url'],
            $d['expires_at'], $d['is_active'], $id, $userId,
        ]);
    }

    /** Cupons visíveis: ativos e não expirados. */
    public static function visibleForUser(int $userId): array
    {
        $stmt = self::pdo()->prepare(
            'SELECT * FROM coupons
              WHERE user_id = ? AND is_active = 1 AND (expires_at IS NULL OR expires_at >= CURDATE())
              ORDER BY position ASC, id ASC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
