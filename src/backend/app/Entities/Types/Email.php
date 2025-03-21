<?php

namespace App\Entities\Types;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $this->validate($email);

        $this->email = $email;
    }

    private function validate(string $email): void
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Formato de email invalido');
        }
    }

    public function getValue(): string
    {
        return $this->email;
    }
}
