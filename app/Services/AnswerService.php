<?php

namespace App\Services;

use App\Repositories\AnswerRepository;

class AnswerService
{
    private AnswerRepository $repository;

    public function __construct()
    {
        $this->repository = new AnswerRepository();
    }

    public function findOne(int $surveyId, int $employeeId, int $questionId): ?array
    {
        return $this->repository->findOne($surveyId, $employeeId, $questionId);
    }

    public function save(array $data): bool
    {
        return $this->repository->save($data);
    }

    public function allForEmployeeSurvey(int $surveyId, int $employeeId): array
    {
        return $this->repository->allForEmployeeSurvey($surveyId, $employeeId);
    }

    public function respondentsForSurvey(int $surveyId): array
    {
        return $this->repository->respondentsForSurvey($surveyId);
    }

    public function countAnsweredQuestions(int $surveyId, int $employeeId): int
    {
        return $this->repository->countAnsweredQuestions($surveyId, $employeeId);
    }

    public function countRespondents(int $surveyId): int
    {
        return $this->repository->countRespondents($surveyId);
    }
}
