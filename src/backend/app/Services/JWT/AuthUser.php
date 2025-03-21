<?php

namespace App\Services\JWT;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthUser implements JWTSubject
{
    private string $token;

    public function __construct(
        private string $id,
        private string $email,
        private ?string $name
    ) {
        $this->token = JWTAuth::fromUser($this);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getJWTIdentifier()
    {
        return $this->id;
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public static function getUser()
    {
        return JWTAuth::getPayload();
    }
}
