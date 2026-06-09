<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/** Registro e agregação de cliques e visualizações de perfil. */
final class Click
{
    public static function record(int $linkId, int $userId, array $meta): void
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO clicks (link_id, user_id, referrer, user_agent, ip_hash, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $linkId,
            $userId,
            $meta['referrer'] ?? null,
            $meta['user_agent'] ?? null,
            $meta['ip_hash'] ?? null,
        ]);
    }

    public static function recordProfileView(int $userId, string $ipHash): void
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO profile_views (user_id, ip_hash, created_at) VALUES (?, ?, NOW())'
        );
        $stmt->execute([$userId, $ipHash]);
    }

    public static function totalClicks(int $userId): int
    {
        $stmt = Database::pdo()->prepare('SELECT COUNT(*) FROM clicks WHERE user_id = ?');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public static function totalViews(int $userId): int
    {
        $stmt = Database::pdo()->prepare('SELECT COUNT(*) FROM profile_views WHERE user_id = ?');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    /** Cliques por dia nos últimos N dias (preenchendo dias sem cliques com 0). */
    public static function clicksPerDay(int $userId, int $days = 14): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT DATE(created_at) AS dia, COUNT(*) AS total
               FROM clicks
              WHERE user_id = ? AND created_at >= (CURDATE() - INTERVAL ? DAY)
              GROUP BY DATE(created_at)'
        );
        $stmt->execute([$userId, $days - 1]);
        $rows = [];
        foreach ($stmt->fetchAll() as $row) {
            $rows[$row['dia']] = (int) $row['total'];
        }

        $series = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} day"));
            $series[] = ['date' => $date, 'total' => $rows[$date] ?? 0];
        }
        return $series;
    }

    /** Links mais clicados do usuário. */
    public static function topLinks(int $userId, int $limit = 5): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, title, url, clicks_count
               FROM links
              WHERE user_id = ?
              ORDER BY clicks_count DESC, id ASC
              LIMIT ?'
        );
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
