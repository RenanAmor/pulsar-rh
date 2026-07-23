<?php

namespace App\Repositories;

use App\Models\Answer;

class AnswerRepository
{
    private Answer $model;

    public function __construct()
    {
        $this->model = new Answer();
    }

    public function findOne(int $surveyId, int $employeeId, int $questionId): ?array
    {
        return $this->model->findOne($surveyId, $employeeId, $questionId);
    }

    public function save(array $data): bool
    {
        return $this->model->save($data);
    }

    public function createMany(array $rows): bool
    {
        return $this->model->createMany($rows);
    }

    public function allForEmployeeSurvey(int $surveyId, int $employeeId): array
    {
        return $this->model->allForEmployeeSurvey($surveyId, $employeeId);
    }

    public function respondentsForSurvey(int $surveyId): array
    {
        return $this->model->respondentsForSurvey($surveyId);
    }

    public function countAnsweredQuestions(int $surveyId, int $employeeId): int
    {
        return $this->model->countAnsweredQuestions($surveyId, $employeeId);
    }

    public function countRespondents(int $surveyId): int
    {
        return $this->model->countRespondents($surveyId);
    }
}
