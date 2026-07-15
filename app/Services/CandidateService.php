<?php

namespace App\Services;

use App\Repositories\CandidateRepository;

class CandidateService
{
    private CandidateRepository $repository;

    public function __construct()
    {
        $this->repository = new CandidateRepository();
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
}