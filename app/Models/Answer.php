<?php

namespace App\Models;

use Database\Database;
use PDO;

class Answer
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findOne(int $surveyId, int $employeeId, int $questionId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM answers
            WHERE survey_id = :survey_id
                AND employee_id = :employee_id
                AND question_id = :question_id
            LIMIT 1
        ");

        $stmt->execute([
            'survey_id'   => $surveyId,
            'employee_id' => $employeeId,
            'question_id' => $questionId
        ]);

        $answer = $stmt->fetch(PDO::FETCH_ASSOC);

        return $answer ?: null;
    }

    public function save(array $data): bool
    {
        $existing = $this->findOne(
            (int) $data['survey_id'],
            (int) $data['employee_id'],
            (int) $data['question_id']
        );

        if ($existing) {
            $stmt = $this->db->prepare("
                UPDATE answers
                SET
                    score = :score,
                    answer_text = :answer_text,
                    answered_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            return $stmt->execute([
                'id'          => $existing['id'],
                'score'       => $data['score'],
                'answer_text' => $data['answer_text']
            ]);
        }

        $stmt = $this->db->prepare("
            INSERT INTO answers
            (
                company_id,
                survey_id,
                employee_id,
                question_id,
                score,
                answer_text
            )
            VALUES
            (
                :company_id,
                :survey_id,
                :employee_id,
                :question_id,
                :score,
                :answer_text
            )
        ");

        return $stmt->execute($data);
    }

    /**
     * Insere várias respostas em lote, sem verificação prévia de duplicidade.
     * Uso exclusivo do Laboratório Organizacional, onde as respostas geradas
     * pertencem sempre a uma pesquisa/empresa recém-criada (não há risco de
     * conflito com uma resposta existente, ao contrário de save()).
     *
     * @param array $rows Cada item com company_id, survey_id, employee_id, question_id, score, answer_text
     */
    public function createMany(array $rows): bool
    {
        if (empty($rows)) {
            return true;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            $placeholders = [];
            $values = [];

            foreach ($chunk as $row) {
                $placeholders[] = '(?, ?, ?, ?, ?, ?)';
                array_push(
                    $values,
                    $row['company_id'],
                    $row['survey_id'],
                    $row['employee_id'],
                    $row['question_id'],
                    $row['score'],
                    $row['answer_text']
                );
            }

            $sql = '
                INSERT INTO answers
                (company_id, survey_id, employee_id, question_id, score, answer_text)
                VALUES ' . implode(', ', $placeholders) . '
            ';

            $stmt = $this->db->prepare($sql);

            if (!$stmt->execute($values)) {
                return false;
            }
        }

        return true;
    }

    public function allForEmployeeSurvey(int $surveyId, int $employeeId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                answers.*,
                questions.category,
                questions.dimension,
                questions.question,
                questions.answer_type
            FROM answers
            INNER JOIN questions
                ON questions.id = answers.question_id
            WHERE answers.survey_id = :survey_id
                AND answers.employee_id = :employee_id
            ORDER BY questions.category, questions.dimension, answers.id
        ");

        $stmt->execute([
            'survey_id'   => $surveyId,
            'employee_id' => $employeeId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function respondentsForSurvey(int $surveyId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                employees.id AS employee_id,
                employees.name AS employee_name,
                COUNT(answers.id) AS answered_count,
                MAX(answers.answered_at) AS last_answered_at
            FROM answers
            INNER JOIN employees
                ON employees.id = answers.employee_id
            WHERE answers.survey_id = :survey_id
            GROUP BY employees.id, employees.name
            ORDER BY employees.name
        ");

        $stmt->execute([
            'survey_id' => $surveyId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAnsweredQuestions(int $surveyId, int $employeeId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT question_id) AS total
            FROM answers
            WHERE survey_id = :survey_id
                AND employee_id = :employee_id
        ");

        $stmt->execute([
            'survey_id'   => $surveyId,
            'employee_id' => $employeeId
        ]);

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countRespondents(int $surveyId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT employee_id) AS total
            FROM answers
            WHERE survey_id = :survey_id
        ");

        $stmt->execute([
            'survey_id' => $surveyId
        ]);

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
