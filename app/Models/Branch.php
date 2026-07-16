<?php

namespace App\Models;

use Database\Database;
use PDO;

class Branch
{
    public static function findById(int $id): ?array
    {
        $db = Database::connect();

        $stmt = $db->prepare('
            SELECT *
            FROM branches
            WHERE id = :id
            LIMIT 1
        ');

        $stmt->execute([
            'id' => $id
        ]);

        $branch = $stmt->fetch(PDO::FETCH_ASSOC);

        return $branch ?: null;
    }

    public static function all(): array
    {
        $db = Database::connect();

        $stmt = $db->query('
            SELECT *
            FROM branches
            ORDER BY name
        ');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
