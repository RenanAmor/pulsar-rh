<?php

namespace App\Models;

use Database\Database;
use PDO;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $db = Database::connect();

        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':email', $email);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $db = Database::connect();

        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function all(): array
    {
        $db = Database::connect();

        $stmt = $db->query("SELECT * FROM users ORDER BY name");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}