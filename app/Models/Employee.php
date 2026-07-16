<?php

namespace App\Models;

use Database\Database;
use PDO;

class Employee
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
                employees.*,
                companies.trade_name AS company_name,
                branches.name AS branch_name,
                departments.name AS department_name,
                positions.name AS position_name,
                managers.name AS manager_name
            FROM employees
            INNER JOIN companies
                ON companies.id = employees.company_id
            INNER JOIN branches
                ON branches.id = employees.branch_id
            INNER JOIN departments
                ON departments.id = employees.department_id
            INNER JOIN positions
                ON positions.id = employees.position_id
            LEFT JOIN employees AS managers
                ON managers.id = employees.manager_id
            ORDER BY
                companies.trade_name,
                branches.name,
                departments.name,
                positions.name,
                employees.name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listForSelect(): array
    {
        $stmt = $this->db->query("
            SELECT
                id,
                company_id,
                branch_id,
                department_id,
                position_id,
                name,
                status
            FROM employees
            ORDER BY name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM employees
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        return $employee ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO employees
            (
                company_id,
                branch_id,
                department_id,
                team_id,
                position_id,
                manager_id,
                registration,
                name,
                cpf,
                birth_date,
                gender,
                email,
                phone,
                admission_date,
                termination_date,
                employment_type,
                status
            )
            VALUES
            (
                :company_id,
                :branch_id,
                :department_id,
                :team_id,
                :position_id,
                :manager_id,
                :registration,
                :name,
                :cpf,
                :birth_date,
                :gender,
                :email,
                :phone,
                :admission_date,
                :termination_date,
                :employment_type,
                :status
            )
        ");

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE employees
            SET
                company_id = :company_id,
                branch_id = :branch_id,
                department_id = :department_id,
                team_id = :team_id,
                position_id = :position_id,
                manager_id = :manager_id,
                registration = :registration,
                name = :name,
                cpf = :cpf,
                birth_date = :birth_date,
                gender = :gender,
                email = :email,
                phone = :phone,
                admission_date = :admission_date,
                termination_date = :termination_date,
                employment_type = :employment_type,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM employees
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function isValidHierarchy(int $companyId, int $branchId, int $departmentId, int $positionId): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM positions
            INNER JOIN departments
                ON departments.id = positions.department_id
            INNER JOIN branches
                ON branches.id = positions.branch_id
            WHERE positions.id = :position_id
                AND positions.company_id = :company_id
                AND positions.branch_id = :branch_id
                AND positions.department_id = :department_id
                AND departments.company_id = :company_id
                AND departments.branch_id = :branch_id
                AND branches.company_id = :company_id
            LIMIT 1
        ");

        $stmt->execute([
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'department_id' => $departmentId,
            'position_id' => $positionId
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
