<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces;

interface TransactionLogRepositoryInterface extends RepositoryInterface
{
    public function findByFilter(array $filter): ?array;
}
