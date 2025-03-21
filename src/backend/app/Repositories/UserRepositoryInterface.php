<?php

namespace App\Repositories;

use App\Entities\Types\CPF;
use App\Entities\Types\Email;
use App\Entities\User;

interface UserRepositoryInterface
{
    public function create(User $user): void;

    public function update(User $user): void;

    public function findByID(string $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function list(string $filterCPF, string $filterName, int $perPage = 10): array;
}
