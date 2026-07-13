<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function all(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): bool
    {
        $data['password'] = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

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