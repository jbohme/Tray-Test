<?php

namespace App\Entities\Types;

class CPF
{
    private string $cpf;

    public function __construct(string $cpf)
    {
        $this->validate($cpf);

        $this->cpf = $cpf;
    }

    private function validate(string $cpf): void
    {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            throw new \DomainException('Comprimento do CPF inválido');
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            throw new \DomainException('Formato do CPF inválido');
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                throw new \DomainException('CPF inválido');
            }
        }
    }

    public function getValue(): string
    {
        return $this->cpf;
    }
}
