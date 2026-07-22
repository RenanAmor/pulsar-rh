<?php

namespace App\Models;

use Database\Database;
use PDO;

class SurveyQuestion
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM survey_questions
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $surveyQuestion = $stmt->fetch(PDO::FETCH_ASSOC);

        return $surveyQuestion ?: null;
    }

    public function allForSurvey(int $surveyId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                survey_questions.*,
                questions.category,
                questions.dimension,
                questions.question,
                questions.answer_type,
                questions.weight
            FROM survey_questions
            INNER JOIN questions
                ON questions.id = survey_questions.question_id
            WHERE survey_questions.survey_id = :survey_id
            ORDER BY survey_questions.display_order, survey_questions.id
        ");

        $stmt->execute([
            'survey_id' => $surveyId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function availableQuestionsForSurvey(int $surveyId, int $companyId): array
    {
        $stmt = $this->db->prepare("
            SELECT questions.*
            FROM questions
            WHERE questions.active = 1
                AND (questions.company_id IS NULL OR questions.company_id = :company_id)
                AND questions.id NOT IN (
                    SELECT question_id
                    FROM survey_questions
                    WHERE survey_id = :survey_id
                )
            ORDER BY questions.category, questions.dimension, questions.id
        ");

        $stmt->execute([
            'survey_id'  => $surveyId,
            'company_id' => $companyId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function nextDisplayOrder(int $surveyId): int
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(MAX(display_order), 0) + 1 AS next_order
            FROM survey_questions
            WHERE survey_id = :survey_id
        ");

        $stmt->execute([
            'survey_id' => $surveyId
        ]);

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['next_order'];
    }

    public function add(int $surveyId, int $questionId): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO survey_questions
            (
                survey_id,
                question_id,
                display_order,
                required
            )
            VALUES
            (
                :survey_id,
                :question_id,
                :display_order,
                1
            )
        ");

        return $stmt->execute([
            'survey_id'     => $surveyId,
            'question_id'   => $questionId,
            'display_order' => $this->nextDisplayOrder($surveyId)
        ]);
    }

    public function remove(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM survey_questions
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function toggleRequired(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE survey_questions
            SET required = 1 - required
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    private function updateDisplayOrder(int $id, int $displayOrder): bool
    {
        $stmt = $this->db->prepare("
            UPDATE survey_questions
            SET display_order = :display_order
            WHERE id = :id
        ");

        return $stmt->execute([
            'id'            => $id,
            'display_order' => $displayOrder
        ]);
    }

    public function moveUp(int $id, int $surveyId): bool
    {
        $current = $this->find($id);

        if (!$current) {
            return false;
        }

        $stmt = $this->db->prepare("
            SELECT *
            FROM survey_questions
            WHERE survey_id = :survey_id
                AND display_order < :display_order
            ORDER BY display_order DESC
            LIMIT 1
        ");

        $stmt->execute([
            'survey_id'     => $surveyId,
            'display_order' => $current['display_order']
        ]);

        $previous = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$previous) {
            return false;
        }

        $this->updateDisplayOrder($current['id'], $previous['display_order']);
        $this->updateDisplayOrder($previous['id'], $current['display_order']);

        return true;
    }

    public function moveDown(int $id, int $surveyId): bool
    {
        $current = $this->find($id);

        if (!$current) {
            return false;
        }

        $stmt = $this->db->prepare("
            SELECT *
            FROM survey_questions
            WHERE survey_id = :survey_id
                AND display_order > :display_order
            ORDER BY display_order ASC
            LIMIT 1
        ");

        $stmt->execute([
            'survey_id'     => $surveyId,
            'display_order' => $current['display_order']
        ]);

        $next = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$next) {
            return false;
        }

        $this->updateDisplayOrder($current['id'], $next['display_order']);
        $this->updateDisplayOrder($next['id'], $current['display_order']);

        return true;
    }

    public function countForSurvey(int $surveyId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM survey_questions
            WHERE survey_id = :survey_id
        ");

        $stmt->execute([
            'survey_id' => $surveyId
        ]);

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
