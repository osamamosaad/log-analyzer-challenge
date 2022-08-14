<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Application\Query;

use App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces\TransactionLogRepositoryInterface;

class GetCountTransactionLogs
{
    public function __construct(
        private TransactionLogRepositoryInterface $transactionLogRepo
    ) {
    }

    public function get(array $filters)
    {
        return $this->transactionLogRepo->findByFilter($filters);
    }
}
