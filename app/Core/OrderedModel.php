<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Base para coleções ordenadas e pertencentes a um usuário
 * (links, depoimentos, FAQs, cupons, etc.). Reúne a lógica repetitiva
 * de leitura, remoção e reordenação. As subclasses definem $table e
 * implementam create()/update() com suas colunas próprias.
 */
abstract class OrderedModel
{
    protected static string $table = '';

    protected static function pdo(): \PDO
    {
        return Database::pdo();
    }

    public static function forUser(int $userId): array
    {
        $stmt = self::pdo()->prepare(
            'SELECT * FROM ' . static::$table . ' WHERE user_id = ? ORDER BY position ASC, id ASC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function activeForUser(int $userId): array
    {
        $stmt = self::pdo()->prepare(
            'SELECT * FROM ' . static::$table . ' WHERE user_id = ? AND is_active = 1 ORDER BY position ASC, id ASC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function findOwned(int $id, int $userId): ?array
    {
        $stmt = self::pdo()->prepare(
            'SELECT * FROM ' . static::$table . ' WHERE id = ? AND user_id = ? LIMIT 1'
        );
        $stmt->execute([$id, $userId]);
        return $stmt->fetch() ?: null;
    }

    public static function countForUser(int $userId): int
    {
        $stmt = self::pdo()->prepare('SELECT COUNT(*) FROM ' . static::$table . ' WHERE user_id = ?');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public static function nextPosition(int $userId): int
    {
        return self::countForUser($userId);
    }

    public static function delete(int $id, int $userId): void
    {
        $stmt = self::pdo()->prepare('DELETE FROM ' . static::$table . ' WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
    }

    public static function toggle(int $id, int $userId): void
    {
        $stmt = self::pdo()->prepare(
            'UPDATE ' . static::$table . ' SET is_active = 1 - is_active WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$id, $userId]);
    }

    /** Move um item uma posição para cima/baixo, reindexando todas as posições. */
    public static function move(int $id, int $userId, string $direction): void
    {
        $rows = static::forUser($userId);
        $index = null;
        foreach ($rows as $i => $row) {
            if ((int) $row['id'] === $id) {
                $index = $i;
                break;
            }
        }
        if ($index === null) {
            return;
        }
        $target = $direction === 'up' ? $index - 1 : $index + 1;
        if ($target < 0 || $target >= count($rows)) {
            return;
        }
        [$rows[$index], $rows[$target]] = [$rows[$target], $rows[$index]];

        $stmt = self::pdo()->prepare('UPDATE ' . static::$table . ' SET position = ? WHERE id = ? AND user_id = ?');
        foreach ($rows as $position => $row) {
            $stmt->execute([$position, (int) $row['id'], $userId]);
        }
    }
}
