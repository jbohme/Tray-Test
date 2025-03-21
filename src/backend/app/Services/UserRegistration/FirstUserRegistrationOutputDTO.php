<?php

namespace App\Services\UserRegistration;

use App\Entities\Types\Email;
use App\Entities\Types\RegistrationStatus;

class FirstUserRegistrationOutputDTO
{
    public function __construct(
        private string $id,
        private Email $email,
        private RegistrationStatus $registrationStatus,
        private ?string $name,
    ) {}

    public function getRegistrationStatus(): RegistrationStatus
    {
        return $this->registrationStatus;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
