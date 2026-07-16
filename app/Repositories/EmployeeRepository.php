<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    private Employee $model;

    public function __construct()
    {
        $this->model = new Employee();
    }

    public function all(): array
    {
        return $this->model->all();
    }

    public function listForSelect(): array
    {
        return $this->model->listForSelect();
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

    public function isValidHierarchy(int $companyId, int $branchId, int $departmentId, int $positionId): bool
    {
        return $this->model->isValidHierarchy($companyId, $branchId, $departmentId, $positionId);
    }
}
