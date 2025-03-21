<?php

namespace Tests\Unit\Entities\Types;

use App\Entities\Types\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_it_should_create_a_valid_email()
    {
        $email = new Email('user@example.com');
        $this->assertEquals('user@example.com', $email->getValue());
    }

    public function test_it_should_throw_exception_for_invalid_email_format()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Formato de email invalido');

        new Email('invalid-email.com');
    }

    public function test_it_should_throw_exception_for_missing_domain()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Formato de email invalido');

        new Email('user@');
    }

    public function test_it_should_throw_exception_for_missing_username()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Formato de email invalido');

        new Email('@example.com');
    }
}
