<?php

namespace App\Entities;

use App\Entities\Types\CPF;
use App\Entities\Types\Email;
use App\Entities\Types\RegistrationStatus;
use DateTime;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

class User implements JsonSerializable
{
    public function __construct(
        protected ?string $id,
        private Email $email,
        private RegistrationStatus $status,
        private string $accessToken,
        private ?string $name = null,
        private ?CPF $cpf = null,
        private ?DateTime $birthdate = null,
    ) {
        if (is_null($id)) {
            $this->id = Uuid::v4();
        }

        if ($this->status === RegistrationStatus::Complete && ! $this->name) {
            throw new \DomainException('O Nome é obrigatório para finalizar o registro');
        }

        if ($this->status === RegistrationStatus::Complete && ! $this->birthdate) {
            throw new \DomainException('A data de nascimento é obrigatório para finalizar o registro');
        }

        if ($this->status === RegistrationStatus::Complete && ! $this->cpf) {
            throw new \DomainException('O CPF é obrigatório para finalizar o registro');
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getStatus(): RegistrationStatus
    {
        return $this->status;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getCpf(): ?CPF
    {
        return $this->cpf;
    }

    public function hasRegistrationBeenCompleted(): bool
    {
        return $this->status === RegistrationStatus::Complete;
    }

    public function hasRegistrationBeenPending(): bool
    {
        return $this->status === RegistrationStatus::Pending;
    }

    public function finalizingRegistration(
        string $name,
        DateTime $birthdate,
        CPF $cpf,
    ): static {
        $this->name = $name;
        $this->birthdate = $birthdate;
        $this->cpf = $cpf;
        $this->status = RegistrationStatus::Complete;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'birthdate' => $this->birthdate->format('d-m-Y'),
            'email' => $this->email->getValue(),
            'status' => $this->status->value,
            'access_token' => $this->accessToken,
            'cpf' => $this->cpf?->getValue(),
        ];
    }
}
