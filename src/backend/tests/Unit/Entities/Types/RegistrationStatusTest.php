<?php

namespace Tests\Unit\Entities\Types;

use App\Entities\Types\RegistrationStatus;
use PHPUnit\Framework\TestCase;

class RegistrationStatusTest extends TestCase
{
    public function test_it_should_have_correct_enum_values()
    {
        $this->assertEquals('complete', RegistrationStatus::Complete->value);
        $this->assertEquals('pending', RegistrationStatus::Pending->value);
    }

    public function test_it_should_be_able_to_instantiate_enum_from_value()
    {
        $this->assertSame(RegistrationStatus::Complete, RegistrationStatus::from('complete'));
        $this->assertSame(RegistrationStatus::Pending, RegistrationStatus::from('pending'));
    }
}
