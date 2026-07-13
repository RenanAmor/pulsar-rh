<?php

namespace App\Repositories;

use Database\Database;
use PDO;

class CompanyRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM companies
            ORDER BY trade_name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listForSelect(): array
    {
        $stmt = $this->db->query("
            SELECT
                id,
                trade_name
            FROM companies
            WHERE active = 1
            ORDER BY trade_name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM companies
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $company = $stmt->fetch(PDO::FETCH_ASSOC);

        return $company ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO companies
            (
                corporate_name,
                trade_name,
                document,
                email,
                phone,
                website,
                sector,
                size,
                employees,
                city,
                state,
                active
            )
            VALUES
            (
                :corporate_name,
                :trade_name,
                :document,
                :email,
                :phone,
                :website,
                :sector,
                :size,
                :employees,
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
            UPDATE companies
            SET
                corporate_name = :corporate_name,
                trade_name = :trade_name,
                document = :document,
                email = :email,
                phone = :phone,
                website = :website,
                sector = :sector,
                size = :size,
                employees = :employees,
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
            DELETE FROM companies
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}