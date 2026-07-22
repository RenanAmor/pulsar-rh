<?php

namespace App\Repositories;

use App\Models\SurveyQuestion;

class SurveyQuestionRepository
{
    private SurveyQuestion $model;

    public function __construct()
    {
        $this->model = new SurveyQuestion();
    }

    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function allForSurvey(int $surveyId): array
    {
        return $this->model->allForSurvey($surveyId);
    }

    public function availableQuestionsForSurvey(int $surveyId, int $companyId): array
    {
        return $this->model->availableQuestionsForSurvey($surveyId, $companyId);
    }

    public function add(int $surveyId, int $questionId): bool
    {
        return $this->model->add($surveyId, $questionId);
    }

    public function remove(int $id): bool
    {
        return $this->model->remove($id);
    }

    public function toggleRequired(int $id): bool
    {
        return $this->model->toggleRequired($id);
    }

    public function moveUp(int $id, int $surveyId): bool
    {
        return $this->model->moveUp($id, $surveyId);
    }

    public function moveDown(int $id, int $surveyId): bool
    {
        return $this->model->moveDown($id, $surveyId);
    }

    public function countForSurvey(int $surveyId): int
    {
        return $this->model->countForSurvey($surveyId);
    }
}
