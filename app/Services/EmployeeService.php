<?php

namespace App\Services;

use App\Repositories\EmployeeRepository;

class EmployeeService
{
    private EmployeeRepository $repository;

    public function __construct()
    {
        $this->repository = new EmployeeRepository();
    }

    public function all(): array
    {
        return $this->repository->all();
    }

    public function listForSelect(): array
    {
        return $this->repository->listForSelect();
    }

    public function find(int $id): ?array
    {
        return $this->repository->find($id);
    }

    public function create(array $data): bool
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function isValidHierarchy(int $companyId, int $branchId, int $departmentId, int $positionId): bool
    {
        return $this->repository->isValidHierarchy($companyId, $branchId, $departmentId, $positionId);
    }
}
