<?php

namespace Tests\Unit\Entities;

use App\Entities\Types\CPF;
use App\Entities\Types\Email;
use App\Entities\Types\RegistrationStatus;
use App\Entities\User;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_it_should_create_a_valid_user()
    {
        $id = Str::uuid();
        $user = new User(
            id: $id,
            email: new Email('jose@example.com'),
            status: RegistrationStatus::Pending,
            accessToken: 'sample_token_123',
            name: 'José Castro',
            cpf: new CPF('123.456.789-09'),
            birthdate: new \DateTime('1990-01-01')
        );

        $this->assertEquals($id, $user->getId());
        $this->assertEquals('José Castro', $user->getName());
        $this->assertEquals('1990-01-01', $user->getBirthdate()->format('Y-m-d'));
        $this->assertInstanceOf(Email::class, $user->getEmail());
        $this->assertEquals(RegistrationStatus::Pending, $user->getStatus());
        $this->assertEquals('sample_token_123', $user->getAccessToken());
        $this->assertInstanceOf(CPF::class, $user->getCpf());
    }
}
