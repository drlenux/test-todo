<?php

declare(strict_types=1);

namespace App\Constants;

enum TaskPriorityEnum: int
{
    case FIRST = 1;
    case SECOND = 2;
    case THIRD = 3;
    case FOURTH = 4;
    case FIFTH = 5;
}
