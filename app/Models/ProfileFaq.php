<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\OrderedModel;

final class ProfileFaq extends OrderedModel
{
    protected static string $table = 'profile_faqs';

    public static function create(int $userId, array $d): int
    {
        $stmt = self::pdo()->prepare(
            'INSERT INTO profile_faqs (user_id, question, answer, position) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $d['question'], $d['answer'], self::nextPosition($userId)]);
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(int $id, int $userId, array $d): void
    {
        $stmt = self::pdo()->prepare(
            'UPDATE profile_faqs SET question = ?, answer = ? WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$d['question'], $d['answer'], $id, $userId]);
    }
}
