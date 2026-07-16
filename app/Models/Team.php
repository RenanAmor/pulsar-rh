<?php

namespace App\Models;

use Database\Database;
use PDO;

class Team
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
                teams.*,
                companies.trade_name AS company_name,
                branches.name AS branch_name,
                departments.name AS department_name,
                leaders.name AS leader_name
            FROM teams
            INNER JOIN companies
                ON companies.id = teams.company_id
            INNER JOIN branches
                ON branches.id = teams.branch_id
            INNER JOIN departments
                ON departments.id = teams.department_id
            LEFT JOIN employees AS leaders
                ON leaders.id = teams.leader_id
            ORDER BY
                companies.trade_name,
                branches.name,
                departments.name,
                teams.name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM teams
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $team = $stmt->fetch(PDO::FETCH_ASSOC);

        return $team ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO teams
            (
                company_id,
                branch_id,
                department_id,
                leader_id,
                name,
                code,
                description,
                active
            )
            VALUES
            (
                :company_id,
                :branch_id,
                :department_id,
                :leader_id,
                :name,
                :code,
                :description,
                :active
            )
        ");

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE teams
            SET
                company_id = :company_id,
                branch_id = :branch_id,
                department_id = :department_id,
                leader_id = :leader_id,
                name = :name,
                code = :code,
                description = :description,
                active = :active
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM teams
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function isValidHierarchy(int $companyId, int $branchId, int $departmentId): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM departments
            INNER JOIN branches
                ON branches.id = departments.branch_id
            WHERE departments.id = :department_id
                AND departments.company_id = :company_id
                AND departments.branch_id = :branch_id
                AND branches.company_id = :company_id
            LIMIT 1
        ");

        $stmt->execute([
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'department_id' => $departmentId
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function isValidLeader(int $leaderId, int $companyId, int $branchId, int $departmentId): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM employees
            WHERE id = :leader_id
                AND company_id = :company_id
                AND branch_id = :branch_id
                AND department_id = :department_id
            LIMIT 1
        ");

        $stmt->execute([
            'leader_id' => $leaderId,
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'department_id' => $departmentId
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
