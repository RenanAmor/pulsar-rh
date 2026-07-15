<?php

namespace App\Repositories;

use Database\Database;
use PDO;

class CandidateRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all(): array
    {
        $sql = "
            SELECT c.*, co.name AS company, j.title AS job
            FROM candidates c
            LEFT JOIN companies co ON co.id = c.company_id
            LEFT JOIN jobs j ON j.id = c.job_id
            ORDER BY c.name
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM candidates
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $candidate = $stmt->fetch();

        return $candidate ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO candidates
            (
                company_id,
                job_id,
                name,
                email,
                phone,
                cpf,
                birth_date,
                city,
                state,
                linkedin,
                resume,
                notes,
                status
            )
            VALUES
            (?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        return $stmt->execute([
            $data['company_id'],
            $data['job_id'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['cpf'],
            $data['birth_date'],
            $data['city'],
            $data['state'],
            $data['linkedin'],
            $data['resume'],
            $data['notes'],
            $data['status']
        ]);
    }
}