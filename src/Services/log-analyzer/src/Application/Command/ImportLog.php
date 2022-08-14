<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Application\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\LogAnalyzer\Exceptions\ImportStoppedException;
use App\Services\LogAnalyzer\Infrastructure\{
    Entities\LogFileStatus,
};
use App\Services\LogAnalyzer\Infrastructure\Entities\TransactionLogMethod;
use App\Services\LogAnalyzer\Libraries\{
    FileReaderInterface,
    LogExtractor,
    LogFile as LogFileLib,
    TransactionLog as TransactionLogLib,
};
use App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces\TransactionLogRepositoryInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportLog
{
    private const BATCH_SIZE = 200;

    public function __construct(
        private EntityManagerInterface $entityManage,
        private FileReaderInterface $fileReader,
        private LogExtractor $dataExporter,
        private LogFileLib $logFileLib,
        private TransactionLogLib $transactionLogLib,
        private TransactionLogRepositoryInterface $transactionLogRepo,
    ) {
    }

    public function exec(string $filePath, ProgressBar $progressBar): bool
    {
        $this->fileReader->open($filePath);

        $logFile = $this->logFileLib->addIfNotExists(
            $filePath,
            $this->fileReader->totalNumberOFLines(),
        );

        switch ($logFile->getStatus()) {
            case LogFileStatus::Done:
                return true;
            case LogFileStatus::Stopped:
                $this->fileReader->startFrom($logFile->getLastLine());
                break;
            case LogFileStatus::InProgress:
                $lastTransactionLog = $this->transactionLogRepo->getLastLineByLogId($logFile->getId());
                if (null != $lastTransactionLog) {
                    $this->fileReader->startFrom($lastTransactionLog->getLineNum());
                }
                break;
        }


        $batchInc = 0;
        $batchSize = self::BATCH_SIZE;
        $progressBar->setMaxSteps($this->fileReader->totalNumberOFLines());
        try {
            while ($this->fileReader->next()) {
                if (0 == $batchInc) {
                    $this->entityManage->beginTransaction();
                }

                $entityLogData = $this->dataExporter->extract($this->fileReader->currentLine());

                if ($entityLogData) {
                    $this->transactionLogLib->add(
                        $logFile,
                        $this->fileReader->lineNumber(),
                        $entityLogData["serviceName"],
                        $entityLogData["endpoint"],
                        TransactionLogMethod::from(strtoupper($entityLogData["method"])),
                        intval($entityLogData["status"]),
                        $entityLogData["http"],
                        date_create($entityLogData["date"]),
                    );
                }

                if (
                    $batchInc == $batchSize ||
                    $this->fileReader->totalNumberOFLines() == $this->fileReader->lineNumber()
                ) {
                    $this->entityManage->commit();
                    sleep(3);
                    $batchInc = 0;
                } else {
                    $batchInc++;
                }
                $progressBar->advance();
            }
        } catch (\Throwable $th) {
            $this->entityManage->rollback();
            $lastLine = $this->fileReader->lineNumber() - $batchInc;
            # change log file state
            $logFile->setLastLine($lastLine);
            $logFile->setStatus(LogFileStatus::Stopped);
            $this->logFileLib->update($logFile);

            throw new ImportStoppedException("Stopped at log-line: {$this->fileReader->lineNumber()}", $th);
        }

        $logFile->setLastLine($this->fileReader->lineNumber());
        $logFile->setStatus(LogFileStatus::Done);
        $this->logFileLib->update($logFile);

        return true;
    }
}
