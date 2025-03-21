<?php

namespace App\Services\ListUsers;

use App\Entities\Types\CPF;

class ListUserInputDTO
{
    public function __construct(
        private ?string $name = null,
        private ?string $cpf = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }
}
