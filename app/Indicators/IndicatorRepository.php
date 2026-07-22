<?php

namespace App\Indicators;

use Database\Database;
use PDO;

class IndicatorRepository
{
    private PDO $db;

    private const SCOPE_COLUMNS = [
        'company_id'    => 'employees.company_id',
        'branch_id'     => 'employees.branch_id',
        'department_id' => 'employees.department_id',
        'team_id'       => 'employees.team_id',
        'manager_id'    => 'employees.manager_id',
        'position_id'   => 'employees.position_id',
    ];

    public function __construct()
    {
        $this->db = Database::connect();
    }

    private function scopeConditions(array $scope): array
    {
        $conditions = [];
        $params = [];

        foreach (self::SCOPE_COLUMNS as $key => $column) {
            if (!empty($scope[$key])) {
                $conditions[] = "{$column} = :{$key}";
                $params[$key] = (int) $scope[$key];
            }
        }

        return [$conditions, $params];
    }

    public function answersForScope(int $surveyId, array $scope = [], ?string $category = null, ?string $dimension = null): array
    {
        [$conditions, $params] = $this->scopeConditions($scope);

        $conditions[] = 'answers.survey_id = :survey_id';
        $params['survey_id'] = $surveyId;

        if ($category !== null) {
            $conditions[] = 'questions.category = :category';
            $params['category'] = $category;
        }

        if ($dimension !== null) {
            $conditions[] = 'questions.dimension = :dimension';
            $params['dimension'] = $dimension;
        }

        $where = implode(' AND ', $conditions);

        $stmt = $this->db->prepare("
            SELECT
                answers.id,
                answers.employee_id,
                answers.question_id,
                answers.score,
                answers.answer_text,
                questions.category,
                questions.dimension,
                questions.answer_type,
                questions.scale_min,
                questions.scale_max,
                questions.weight
            FROM answers
            INNER JOIN questions
                ON questions.id = answers.question_id
            INNER JOIN employees
                ON employees.id = answers.employee_id
            WHERE {$where}
        ");

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eligibleEmployees(array $scope): int
    {
        [$conditions, $params] = $this->scopeConditions($scope);

        $conditions[] = "employees.status = 'Ativo'";

        $where = implode(' AND ', $conditions);

        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM employees
            WHERE {$where}
        ");

        $stmt->execute($params);

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function respondedEmployees(int $surveyId, array $scope = []): int
    {
        [$conditions, $params] = $this->scopeConditions($scope);

        $conditions[] = 'answers.survey_id = :survey_id';
        $params['survey_id'] = $surveyId;

        $where = implode(' AND ', $conditions);

        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT answers.employee_id) AS total
            FROM answers
            INNER JOIN employees
                ON employees.id = answers.employee_id
            WHERE {$where}
        ");

        $stmt->execute($params);

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function saveSnapshot(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO organizational_health_index
            (
                company_id,
                survey_id,
                leadership,
                communication,
                engagement,
                wellbeing,
                development,
                culture,
                collaboration,
                recognition,
                turnover_risk,
                burnout_risk,
                final_score,
                classification
            )
            VALUES
            (
                :company_id,
                :survey_id,
                :leadership,
                :communication,
                :engagement,
                :wellbeing,
                :development,
                :culture,
                :collaboration,
                :recognition,
                :turnover_risk,
                :burnout_risk,
                :final_score,
                :classification
            )
        ");

        return $stmt->execute($data);
    }

    public function latestSnapshot(int $companyId, ?int $surveyId = null): ?array
    {
        $conditions = ['company_id = :company_id'];
        $params = ['company_id' => $companyId];

        if ($surveyId !== null) {
            $conditions[] = 'survey_id = :survey_id';
            $params['survey_id'] = $surveyId;
        }

        $where = implode(' AND ', $conditions);

        $stmt = $this->db->prepare("
            SELECT *
            FROM organizational_health_index
            WHERE {$where}
            ORDER BY created_at DESC, id DESC
            LIMIT 1
        ");

        $stmt->execute($params);

        $snapshot = $stmt->fetch(PDO::FETCH_ASSOC);

        return $snapshot ?: null;
    }

    public function historySnapshots(int $companyId, ?int $surveyId = null): array
    {
        $conditions = ['company_id = :company_id'];
        $params = ['company_id' => $companyId];

        if ($surveyId !== null) {
            $conditions[] = 'survey_id = :survey_id';
            $params['survey_id'] = $surveyId;
        }

        $where = implode(' AND ', $conditions);

        $stmt = $this->db->prepare("
            SELECT *
            FROM organizational_health_index
            WHERE {$where}
            ORDER BY created_at ASC, id ASC
        ");

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
