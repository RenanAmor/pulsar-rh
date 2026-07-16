<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;

class DepartmentService
{
    private DepartmentRepository $repository;

    public function __construct()
    {
        $this->repository = new DepartmentRepository();
    }

    public function all(): array
    {
        return $this->repository->all();
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
}
