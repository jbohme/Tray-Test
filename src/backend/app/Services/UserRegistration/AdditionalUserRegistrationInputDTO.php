<?php

namespace App\Services\UserRegistration;

use App\Entities\Types\CPF;

class AdditionalUserRegistrationInputDTO
{
    public function __construct(
        private string $id,
        private string $name,
        private CPF $cpf,
        private readonly \DateTime $birthdate,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCpf(): CPF
    {
        return $this->cpf;
    }

    public function getBirthdate(): \DateTime
    {
        return $this->birthdate;
    }
}
