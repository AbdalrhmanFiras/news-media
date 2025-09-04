<?php

namespace App\Enum;

enum AccountStatus: string
{

    case Pending = 'pending';
    case Active = 'active';
    case Blocked = 'blocked';
}
