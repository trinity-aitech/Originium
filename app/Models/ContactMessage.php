<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ContactMessage
{
    public static function create(int $userId, array $payload, ?string $ipHash): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO contact_messages (user_id, payload, ip_hash, created_at) VALUES (?, ?, ?, NOW())'
        );
        $stmt->execute([$userId, json_encode($payload, JSON_UNESCAPED_UNICODE), $ipHash]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function forUser(int $userId): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT * FROM contact_messages WHERE user_id = ? ORDER BY created_at DESC, id DESC'
        );
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            $row['data'] = json_decode($row['payload'], true) ?: [];
        }
        return $rows;
    }

    public static function unreadCount(int $userId): int
    {
        $stmt = Database::pdo()->prepare('SELECT COUNT(*) FROM contact_messages WHERE user_id = ? AND is_read = 0');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public static function markAllRead(int $userId): void
    {
        $stmt = Database::pdo()->prepare('UPDATE contact_messages SET is_read = 1 WHERE user_id = ?');
        $stmt->execute([$userId]);
    }

    public static function delete(int $id, int $userId): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM contact_messages WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
    }

    /** Limite anti-spam: máximo de envios por IP/usuário na última hora. */
    public static function recentCount(int $userId, string $ipHash): int
    {
        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*) FROM contact_messages
              WHERE user_id = ? AND ip_hash = ? AND created_at >= (NOW() - INTERVAL 1 HOUR)'
        );
        $stmt->execute([$userId, $ipHash]);
        return (int) $stmt->fetchColumn();
    }
}
