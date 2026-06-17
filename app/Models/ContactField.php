<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\OrderedModel;

final class ContactField extends OrderedModel
{
    protected static string $table = 'contact_fields';

    public const TYPES = ['text' => 'Texto', 'email' => 'E-mail', 'tel' => 'Telefone', 'textarea' => 'Mensagem longa'];

    public static function create(int $userId, array $d): int
    {
        $stmt = self::pdo()->prepare(
            'INSERT INTO contact_fields (user_id, label, field_type, placeholder, is_required, position)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $userId, $d['label'], $d['field_type'], $d['placeholder'],
            $d['is_required'], self::nextPosition($userId),
        ]);
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(int $id, int $userId, array $d): void
    {
        $stmt = self::pdo()->prepare(
            'UPDATE contact_fields SET label = ?, field_type = ?, placeholder = ?, is_required = ?
              WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$d['label'], $d['field_type'], $d['placeholder'], $d['is_required'], $id, $userId]);
    }
}
