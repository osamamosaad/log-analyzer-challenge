<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces;

use App\Services\LogAnalyzer\Infrastructure\Entities\LogFile;

interface LogFileRepositoryInterface extends RepositoryInterface
{
    public function getByUniqueName(string $uniqueName): ?LogFile;
}
