<?php

namespace App\Services\UserRegistration;

use App\Entities\Types\RegistrationStatus;
use App\Entities\User;
use App\Repositories\UserRepositoryInterface;

class FirstUserRegistrationService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(FirstUserRegistrationInputDTO $inputDTO): FirstUserRegistrationOutputDTO
    {
        $user = $this->userRepository->findByEmail($inputDTO->getEmail());
        if (! is_null($user)) {
            return new FirstUserRegistrationOutputDTO(
                id: $user->getId(),
                email: $user->getEmail(),
                registrationStatus: $user->getStatus(),
                name: $user->getName()
            );
        }

        $user = new User(
            id: null,
            email: $inputDTO->getEmail(),
            status: RegistrationStatus::Pending,
            accessToken: $inputDTO->getAccessToken()
        );

        $this->userRepository->create($user);

        return new FirstUserRegistrationOutputDTO(
            id: $user->getId(),
            email: $user->getEmail(),
            registrationStatus: $user->getStatus(),
            name: $user->getName()
        );
    }
}
