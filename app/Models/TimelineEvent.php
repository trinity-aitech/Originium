<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\OrderedModel;

final class TimelineEvent extends OrderedModel
{
    protected static string $table = 'timeline_events';

    public static function create(int $userId, array $d): int
    {
        $stmt = self::pdo()->prepare(
            'INSERT INTO timeline_events (user_id, period, title, description, position)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $d['period'], $d['title'], $d['description'], self::nextPosition($userId)]);
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(int $id, int $userId, array $d): void
    {
        $stmt = self::pdo()->prepare(
            'UPDATE timeline_events SET period = ?, title = ?, description = ? WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$d['period'], $d['title'], $d['description'], $id, $userId]);
    }
}
