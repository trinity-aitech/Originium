<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Link
{
    public static function forUser(int $userId, bool $activeOnly = false): array
    {
        $sql = 'SELECT * FROM links WHERE user_id = ?';
        if ($activeOnly) {
            $sql .= ' AND is_active = 1';
        }
        $sql .= ' ORDER BY position ASC, id ASC';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM links WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function findOwned(int $id, int $userId): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM links WHERE id = ? AND user_id = ? LIMIT 1');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch() ?: null;
    }

    public static function countForUser(int $userId): int
    {
        $stmt = Database::pdo()->prepare('SELECT COUNT(*) FROM links WHERE user_id = ?');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public static function create(int $userId, array $d): int
    {
        $position = self::countForUser($userId);
        $stmt = Database::pdo()->prepare(
            'INSERT INTO links (user_id, title, url, icon, position, is_active, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())'
        );
        $stmt->execute([
            $userId,
            $d['title'],
            $d['url'],
            $d['icon'],
            $position,
            $d['is_active'],
        ]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function update(int $id, int $userId, array $d): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE links
                SET title = ?, url = ?, icon = ?, is_active = ?, updated_at = NOW()
              WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([
            $d['title'],
            $d['url'],
            $d['icon'],
            $d['is_active'],
            $id,
            $userId,
        ]);
    }

    public static function delete(int $id, int $userId): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM links WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
    }

    public static function toggle(int $id, int $userId): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE links SET is_active = 1 - is_active, updated_at = NOW() WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$id, $userId]);
    }

    public static function incrementClicks(int $id): void
    {
        $stmt = Database::pdo()->prepare('UPDATE links SET clicks_count = clicks_count + 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    /** Move um link uma posição para cima/baixo, reindexando todas as posições. */
    public static function move(int $id, int $userId, string $direction): void
    {
        $links = self::forUser($userId);
        $index = null;
        foreach ($links as $i => $link) {
            if ((int) $link['id'] === $id) {
                $index = $i;
                break;
            }
        }
        if ($index === null) {
            return;
        }
        $target = $direction === 'up' ? $index - 1 : $index + 1;
        if ($target < 0 || $target >= count($links)) {
            return;
        }

        [$links[$index], $links[$target]] = [$links[$target], $links[$index]];

        $pdo = Database::pdo();
        $stmt = $pdo->prepare('UPDATE links SET position = ? WHERE id = ? AND user_id = ?');
        foreach ($links as $position => $link) {
            $stmt->execute([$position, (int) $link['id'], $userId]);
        }
    }
}
