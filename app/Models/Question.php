<?php

namespace App\Models;

use Database\Database;
use PDO;

class Question
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
                questions.*,
                companies.trade_name AS company_name
            FROM questions
            LEFT JOIN companies
                ON companies.id = questions.company_id
            ORDER BY
                questions.category,
                questions.dimension,
                questions.id
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM questions
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        return $question ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO questions
            (
                company_id,
                category,
                dimension,
                question,
                answer_type,
                scale_min,
                scale_max,
                weight,
                active
            )
            VALUES
            (
                :company_id,
                :category,
                :dimension,
                :question,
                :answer_type,
                :scale_min,
                :scale_max,
                :weight,
                :active
            )
        ");

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        $stmt = $this->db->prepare("
            UPDATE questions
            SET
                company_id = :company_id,
                category = :category,
                dimension = :dimension,
                question = :question,
                answer_type = :answer_type,
                scale_min = :scale_min,
                scale_max = :scale_max,
                weight = :weight,
                active = :active
            WHERE id = :id
        ");

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM questions
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function countSurveysUsingQuestion(int $id): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM survey_questions
            WHERE question_id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
