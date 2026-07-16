<?php

namespace App\Services;

use App\Repositories\TeamRepository;

class TeamService
{
    private TeamRepository $repository;

    public function __construct()
    {
        $this->repository = new TeamRepository();
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

    public function isValidHierarchy(int $companyId, int $branchId, int $departmentId): bool
    {
        return $this->repository->isValidHierarchy($companyId, $branchId, $departmentId);
    }

    public function isValidLeader(int $leaderId, int $companyId, int $branchId, int $departmentId): bool
    {
        return $this->repository->isValidLeader($leaderId, $companyId, $branchId, $departmentId);
    }
}
