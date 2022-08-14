<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries;

use App\Services\LogAnalyzer\{
    Infrastructure\Entities\LogFile as LogFileEntity,
    Infrastructure\Entities\LogFileStatus,
};
use App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces\LogFileRepositoryInterface;

class LogFile
{
    public function __construct(
        private LogFileRepositoryInterface $repo
    ) {
    }

    public function addIfNotExists(string $filePath, int $totalNumberOflines): LogFileEntity
    {
        $uniqueName = $this->generateUniqueName($filePath, $totalNumberOflines);
        if ($logFile = $this->repo->getByUniqueName($uniqueName)) {
            return $logFile;
        }

        $logFile = new LogFileEntity();
        $logFile->setFileName($filePath)
            ->setUniqueName($uniqueName)
            ->setStatus(LogFileStatus::InProgress)
            ->setTotalLines($totalNumberOflines)
            ->setLastLine(0)
            ->setCreatedAt(date_create())
            ->setUpdatedAt(date_create());

        $this->repo->save($logFile);

        return $logFile;
    }

    public function update(LogFileEntity $entity): LogFileEntity
    {
        $entity->setUpdatedAt(date_create());
        $this->repo->save($entity);
        return $entity;
    }

    protected function generateUniqueName(string $filePath, int $totalNumberOflines): string
    {
        return md5("{$filePath}{$totalNumberOflines}");
    }
}
