<?php

namespace App\Models;

use Database\Database;
use PDO;

class Branch
{
private PDO $db;

public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT
                branches.*,
                companies.trade_name AS company_name
            FROM branches
            INNER JOIN companies
                ON companies.id = branches.company_id
            ORDER BY companies.trade_name, branches.name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM branches
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $branch = $stmt->fetch(PDO::FETCH_ASSOC);

        return $branch ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO branches
            (
                company_id,
                name,
                document,
                email,
                phone,
                city,
                state,
                active
            )
            VALUES
            (
                :company_id,
                :name,
                :document,
                :email,
                :phone,
                :city,
                :state,
                :active
            )
        ");

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE branches
            SET
                company_id = :company_id,
                name = :name,
                document = :document,
                email = :email,
                phone = :phone,
                city = :city,
                state = :state,
                active = :active
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM branches
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
