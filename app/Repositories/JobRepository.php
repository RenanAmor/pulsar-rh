<?php

namespace App\Repositories;

use Database\Database;
use PDO;

class JobRepository
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
                jobs.*,
                companies.trade_name AS company
            FROM jobs
            INNER JOIN companies
                ON companies.id = jobs.company_id
            ORDER BY jobs.created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM jobs
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        return $job ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO jobs
            (
                company_id,
                title,
                department,
                workplace,
                contract_type,
                vacancies,
                salary,
                city,
                state,
                description,
                requirements,
                benefits,
                active
            )
            VALUES
            (
                :company_id,
                :title,
                :department,
                :workplace,
                :contract_type,
                :vacancies,
                :salary,
                :city,
                :state,
                :description,
                :requirements,
                :benefits,
                :active
            )
        ");

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE jobs
            SET
                company_id = :company_id,
                title = :title,
                department = :department,
                workplace = :workplace,
                contract_type = :contract_type,
                vacancies = :vacancies,
                salary = :salary,
                city = :city,
                state = :state,
                description = :description,
                requirements = :requirements,
                benefits = :benefits,
                active = :active
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM jobs
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}