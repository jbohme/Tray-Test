<?php

namespace Tests\Unit\Entities\Types;

use App\Entities\Types\CPF;
use DomainException;
use PHPUnit\Framework\TestCase;

class CPFTest extends TestCase
{
    public function test_it_should_create_a_valid_cpf(): void
    {
        $cpf = new CPF('123.456.789-09');
        $this->assertEquals('123.456.789-09', $cpf->getValue());
    }

    public function test_it_should_throw_exception_for_invalid_length(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Comprimento do CPF inválido');

        new CPF('12345');
    }

    public function test_it_should_throw_exception_for_repeated_digits(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Formato do CPF inválido');

        new CPF('111.111.111-11');
    }

    public function test_it_should_throw_exception_for_invalid_cpf(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('CPF inválido');

        new CPF('123.456.789-00');
    }
}
