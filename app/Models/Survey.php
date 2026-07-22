<?php

namespace App\Models;

use Database\Database;
use PDO;

class Survey
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
                surveys.*,
                companies.trade_name AS company_name
            FROM surveys
            INNER JOIN companies
                ON companies.id = surveys.company_id
            ORDER BY companies.trade_name, surveys.created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM surveys
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $survey = $stmt->fetch(PDO::FETCH_ASSOC);

        return $survey ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO surveys
            (
                company_id,
                title,
                description,
                start_date,
                end_date,
                anonymous,
                status
            )
            VALUES
            (
                :company_id,
                :title,
                :description,
                :start_date,
                :end_date,
                :anonymous,
                :status
            )
        ");

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE surveys
            SET
                company_id = :company_id,
                title = :title,
                description = :description,
                start_date = :start_date,
                end_date = :end_date,
                anonymous = :anonymous,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM surveys
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
