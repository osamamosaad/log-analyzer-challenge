<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces;

use App\Services\LogAnalyzer\Infrastructure\Entities\TransactionLog;

interface TransactionLogRepositoryInterface extends RepositoryInterface
{
    public function findByFilter(array $filter): ?array;
    public function getLastLineByLogId(int $logId): ?TransactionLog;
}
