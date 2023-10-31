<?php

namespace App\Enums;

use App\Traits\IsEnumTrait;

enum UserType: string
{
    use IsEnumTrait;

    case ADMIN       = 'ADMIN';
    case STORE_OWNER = 'STORE_OWNER';
    case EMPLOYEE    = 'EMPLOYEE';
}
