<?php

namespace App\Services\UserRegistration;

use App\Mail\UserRegistrationCompletedEmail;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class AdditionalUserRegistrationService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(AdditionalUserRegistrationInputDTO $inputDTO): void
    {
        $user = $this->userRepository->findByID($inputDTO->getId());
        if (is_null($user)) {
            throw new \DomainException('Usuário não encontrado');
        }

        if ($user->hasRegistrationBeenCompleted()) {
            throw new \DomainException('Cadastro do usuário já foi realizado');
        }

        $user = $user->finalizingRegistration(
            name: $inputDTO->getName(),
            birthdate: $inputDTO->getBirthdate(),
            cpf: $inputDTO->getCpf(),
        );

        $this->userRepository->update($user);

        Mail::to($user->getEmail()->getValue())
            ->queue(new UserRegistrationCompletedEmail(name: $user->getName()));
    }
}
