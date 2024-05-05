<?php

declare(strict_types=1);

namespace App\Constants;

enum TaskStateEnum: string
{
    case TODO = 'todo';
    case DONE = 'done';
}
