<?php

namespace App\Services;

use App\Repositories\SurveyQuestionRepository;

class SurveyQuestionService
{
    private SurveyQuestionRepository $repository;

    public function __construct()
    {
        $this->repository = new SurveyQuestionRepository();
    }

    public function find(int $id): ?array
    {
        return $this->repository->find($id);
    }

    public function allForSurvey(int $surveyId): array
    {
        return $this->repository->allForSurvey($surveyId);
    }

    public function availableQuestionsForSurvey(int $surveyId, int $companyId): array
    {
        return $this->repository->availableQuestionsForSurvey($surveyId, $companyId);
    }

    public function add(int $surveyId, int $questionId): bool
    {
        return $this->repository->add($surveyId, $questionId);
    }

    public function remove(int $id): bool
    {
        return $this->repository->remove($id);
    }

    public function toggleRequired(int $id): bool
    {
        return $this->repository->toggleRequired($id);
    }

    public function moveUp(int $id, int $surveyId): bool
    {
        return $this->repository->moveUp($id, $surveyId);
    }

    public function moveDown(int $id, int $surveyId): bool
    {
        return $this->repository->moveDown($id, $surveyId);
    }

    public function countForSurvey(int $surveyId): int
    {
        return $this->repository->countForSurvey($surveyId);
    }
}
