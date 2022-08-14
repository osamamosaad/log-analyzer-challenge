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

class ImportLog
{
    private $logFile;

    public function __construct(
        private EntityManagerInterface $entityManage,
        private FileReaderInterface $fileReader,
        private LogExtractor $dataExporter,
        private LogFileLib $logFileLib,
        private TransactionLogLib $transactionLogLib,
    ) {
    }

    public function exec(string $filePath): bool
    {
        $this->fileReader->open($filePath);

        $logFile = $this->logFileLib->addIfNotExists(
            $filePath,
            $this->fileReader->totalNumberOFLines(),
        );
        $this->logFile = $logFile;
        if ($logFile->getStatus() == LogFileStatus::Done) {
            return true;
        }

        if ($logFile->getStatus() == LogFileStatus::Stopped) {
            $this->fileReader->startFrom($logFile->getLastLine());
        }

        $batchInc = 0;
        $batchSize = 100;
        try {
            while ($this->fileReader->next()) {
                if (0 == $batchInc) {
                    $this->entityManage->beginTransaction();
                }

                $entityLogData = $this->dataExporter->extract($this->fileReader->currentLine());
                // dd($this->fileReader->lineNumber());
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

    public function __destruct()
    {
        $this->logFile->setStatus(LogFileStatus::Stopped);
        $this->logFileLib->update($this->logFile);
    }
}
