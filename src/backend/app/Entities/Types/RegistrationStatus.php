<?php

namespace App\Entities\Types;

enum RegistrationStatus: string
{
    case Complete = 'complete';
    case Pending = 'pending';
}
