<?php

namespace App\Models;

use Database\Database;
use PDO;

class Position
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
                positions.*,
                companies.trade_name AS company_name,
                branches.name AS branch_name,
                departments.name AS department_name
            FROM positions
            INNER JOIN companies
                ON companies.id = positions.company_id
            INNER JOIN branches
                ON branches.id = positions.branch_id
            INNER JOIN departments
                ON departments.id = positions.department_id
            ORDER BY
                companies.trade_name,
                branches.name,
                departments.name,
                positions.name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM positions
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $position = $stmt->fetch(PDO::FETCH_ASSOC);

        return $position ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO positions
            (
                company_id,
                branch_id,
                department_id,
                name,
                code,
                cbo,
                description,
                salary_min,
                salary_max,
                active
            )
            VALUES
            (
                :company_id,
                :branch_id,
                :department_id,
                :name,
                :code,
                :cbo,
                :description,
                :salary_min,
                :salary_max,
                :active
            )
        ");

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE positions
            SET
                company_id = :company_id,
                branch_id = :branch_id,
                department_id = :department_id,
                name = :name,
                code = :code,
                cbo = :cbo,
                description = :description,
                salary_min = :salary_min,
                salary_max = :salary_max,
                active = :active
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM positions
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
