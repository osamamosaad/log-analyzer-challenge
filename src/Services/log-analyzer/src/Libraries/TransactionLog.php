<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries;

use App\Services\LogAnalyzer\Infrastructure\{
    Entities\TransactionLog as TransactionLogEntity,
    Entities\TransactionLogMethod,
    Entities\LogFile,
};
use App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces\TransactionLogRepositoryInterface;
use DateTimeInterface;

class TransactionLog
{
    public function __construct(
        private TransactionLogRepositoryInterface $repo
    ) {
    }

    public function add(
        LogFile $logFile,
        int $lineNumber,
        string $serviceName,
        string $endpoint,
        TransactionLogMethod $method,
        int $statusCode,
        string $httpVersion,
        DateTimeInterface $logDate,
    ): TransactionLogEntity {

        $trasactionLog = new TransactionLogEntity();
        $trasactionLog
            ->setLogFile($logFile)
            ->setLineNum($lineNumber)
            ->setServiceName($serviceName)
            ->setEndpoint($endpoint)
            ->setMethod($method)
            ->setStatusCode($statusCode)
            ->setHttpVersion($httpVersion)
            ->setLogDate($logDate)
            ->setCreatedAt(date_create());

        $this->repo->save($trasactionLog);

        return $trasactionLog;
    }
}
