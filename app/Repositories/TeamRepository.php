<?php

namespace App\Repositories;

use App\Models\Team;

class TeamRepository
{
    private Team $model;

    public function __construct()
    {
        $this->model = new Team();
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

    public function isValidHierarchy(int $companyId, int $branchId, int $departmentId): bool
    {
        return $this->model->isValidHierarchy($companyId, $branchId, $departmentId);
    }

    public function isValidLeader(int $leaderId, int $companyId, int $branchId, int $departmentId): bool
    {
        return $this->model->isValidLeader($leaderId, $companyId, $branchId, $departmentId);
    }
}
