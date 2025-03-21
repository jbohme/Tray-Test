<?php

namespace App\Services\ListUsers;

use App\Repositories\UserRepositoryInterface;

class ListUsersService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(ListUserInputDTO $inputDTO): array
    {
        return $this->userRepository->list(filterCPF: $inputDTO->getCpf(), filterName: $inputDTO->getName());
    }
}
