<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Infrastructure\Entities;

enum LogFileStatus: string
{
    case InProgress = 'in-progress';
    case Stopped    = 'stopped';
    case Done       = 'done';
}
