<?php

namespace App\Models;

use Database\Database;
use PDO;

class Department
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
                departments.*,
                companies.trade_name AS company_name,
                branches.name AS branch_name
            FROM departments
            INNER JOIN companies
                ON companies.id = departments.company_id
            INNER JOIN branches
                ON branches.id = departments.branch_id
            ORDER BY
                companies.trade_name,
                branches.name,
                departments.name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM departments
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $department = $stmt->fetch(PDO::FETCH_ASSOC);

        return $department ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO departments
            (
                company_id,
                branch_id,
                manager_id,
                name,
                code,
                description,
                email,
                phone,
                active
            )
            VALUES
            (
                :company_id,
                :branch_id,
                :manager_id,
                :name,
                :code,
                :description,
                :email,
                :phone,
                :active
            )
        ");

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE departments
            SET
                company_id = :company_id,
                branch_id = :branch_id,
                manager_id = :manager_id,
                name = :name,
                code = :code,
                description = :description,
                email = :email,
                phone = :phone,
                active = :active
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM departments
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
