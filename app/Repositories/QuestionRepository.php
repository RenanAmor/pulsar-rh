<?php

namespace App\Repositories;

use App\Models\Question;

class QuestionRepository
{
    private Question $model;

    public function __construct()
    {
        $this->model = new Question();
    }

    public function all(): array
    {
        return $this->model->all();
    }

    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function create(array $data): bool
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function countSurveysUsingQuestion(int $id): int
    {
        return $this->model->countSurveysUsingQuestion($id);
    }
}
