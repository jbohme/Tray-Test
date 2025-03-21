<?php

namespace App\Services\UserRegistration;

use App\Entities\Types\Email;

class FirstUserRegistrationInputDTO
{
    public function __construct(
        private Email $email,
        private string $accessToken
    ) {}

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
