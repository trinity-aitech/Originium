<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\OrderedModel;

final class GalleryImage extends OrderedModel
{
    protected static string $table = 'gallery_images';

    public static function create(int $userId, array $d): int
    {
        $stmt = self::pdo()->prepare(
            'INSERT INTO gallery_images (user_id, image_path, caption, position) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $d['image_path'], $d['caption'], self::nextPosition($userId)]);
        return (int) self::pdo()->lastInsertId();
    }

    public static function updateCaption(int $id, int $userId, string $caption): void
    {
        $stmt = self::pdo()->prepare('UPDATE gallery_images SET caption = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$caption, $id, $userId]);
    }
}
