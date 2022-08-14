<?php

declare(strict_types=1);

use App\Services\LogAnalyzer\Application\Command\ImportLog;
use App\Services\LogAnalyzer\Infrastructure\Adapters\ProgressBarInterface;
use App\Services\LogAnalyzer\Infrastructure\Entities\LogFile as LogFileEntity;
use App\Services\LogAnalyzer\Infrastructure\Entities\LogFileStatus;
use App\Services\LogAnalyzer\Infrastructure\Entities\TransactionLog as TransactionLogEntity;
use App\Services\LogAnalyzer\Libraries\FileReader;
use App\Services\LogAnalyzer\Libraries\FileReaderInterface;
use App\Services\LogAnalyzer\Libraries\LogExtractor;
use App\Services\LogAnalyzer\Libraries\LogFile;
use App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces\TransactionLogRepositoryInterface;
use App\Services\LogAnalyzer\Libraries\TransactionLog;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportLogTest extends TestCase
{
    private const FILEPATH = __DIR__ . "/test-log.log";
    private $entityManagerMock;
    private $fileReaderMock;
    private $dataExporterMock;
    private $logFileLib;
    private $transactionLogLibMock;
    private $transactionLogRepoMock;

    protected function setUp(): void
    {
        /** @var EntityManagerInterface */
        $this->entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()->getMock();
        /** @var FileReaderInterface */
        $this->fileReaderMock = $this->getMockBuilder(FileReaderInterface::class)
            ->disableOriginalConstructor()->getMock();
        /** @var LogExtractor */
        $this->dataExporterMock = $this->getMockBuilder(LogExtractor::class)
            ->disableOriginalConstructor()->getMock();
        /** @var LogFile */
        $this->logFileLib = $this->getMockBuilder(LogFile::class)
            ->disableOriginalConstructor()->getMock();
        /** @var TransactionLog */
        $this->transactionLogLibMock = $this->getMockBuilder(TransactionLog::class)
            ->disableOriginalConstructor()->getMock();
        /**  @var TransactionLogRepositoryInterface */
        $this->transactionLogRepoMock = $this->getMockBuilder(TransactionLogRepositoryInterface::class)
            ->disableOriginalConstructor()->getMock();
    }

    protected function getLogEntity(LogFileStatus $status = LogFileStatus::InProgress)
    {
        // Change id accessibility
        $property = new ReflectionProperty(LogFileEntity::class, "id");
        $property->setAccessible(true);

        $entityLog = new LogFileEntity();
        $property->setValue($entityLog, 100);

        $entityLog->setStatus($status);
        return $entityLog;
    }

    protected function getTrasactionLogEntity()
    {
        // Change id accessibility
        $property = new ReflectionProperty(TransactionLogEntity::class, "id");
        $property->setAccessible(true);

        $entityLog = new TransactionLogEntity();
        $property->setValue($entityLog, 100);

        return $entityLog;
    }

    public function testFileOpenInvoced(): void
    {
        $this->fileReaderMock->expects($this->once())->method("open");
        $this->logFileLib->method("addIfNotExists")->willReturn($this->getLogEntity());
        $this->runService();
    }

    public function testReturnTrueIfLogStatusDone(): void
    {
        $this->logFileLib->method("addIfNotExists")->willReturn($this->getLogEntity(LogFileStatus::Done));
        $status = $this->runService();
        $this->assertTrue($status);
    }

    public function testLogStopStatusStartsFromLastLine(): void
    {
        $logEntity = $this->getLogEntity(LogFileStatus::Stopped);
        $logEntity->setLastLine(100);
        $this->logFileLib->method("addIfNotExists")->willReturn($logEntity);

        $this->fileReaderMock->expects($this->any())->method("startFrom")->with(100);

        $this->runService();
        $this->assertTrue(true);
    }

    public function testLogInprogressStatusStartsFromLastLine(): void
    {
        $logEntity = $this->getLogEntity(LogFileStatus::InProgress);
        $transactionLog = $this->getTrasactionLogEntity();
        $transactionLog->setLineNum(100);

        $this->logFileLib->method("addIfNotExists")->willReturn($logEntity);
        $this->transactionLogRepoMock->method("getLastLineByLogId")->willReturn($transactionLog);

        $this->fileReaderMock->expects($this->any())->method("startFrom")->with(100);

        $this->runService();
        $this->assertTrue(true);
    }

    public function testCallAddTrasactionLog()
    {
        $logEntity = $this->getLogEntity(LogFileStatus::Stopped);
        $logEntity->setLastLine(11);

        $this->logFileLib->method("addIfNotExists")->willReturn($logEntity);
        $this->transactionLogLibMock->expects($this->atLeastOnce())->method("add");

        $this->fileReaderMock = new FileReader();
        $this->dataExporterMock = new LogExtractor();

        $this->runService();
    }

    public function testCallUpdateLogFile()
    {
        $logEntity = $this->getLogEntity(LogFileStatus::Stopped);
        $logEntity->setLastLine(11);

        $this->logFileLib->method("addIfNotExists")->willReturn($logEntity);
        $this->logFileLib->expects($this->atLeastOnce())->method("update");

        $this->fileReaderMock = new FileReader();
        $this->dataExporterMock = new LogExtractor();

        $this->runService();
    }

    public function testEntityManagerTrasactionWorksCorrectly()
    {
        $fileReader = new FileReader();
        $fileReader->open(self::FILEPATH);
        $batchSize = 5;
        $numberOfTimes = round($fileReader->totalNumberOFLines() / $batchSize);

        $logEntity = $this->getLogEntity(LogFileStatus::InProgress);
        $logEntity->setLastLine(0);

        $this->logFileLib->method("addIfNotExists")->willReturn($logEntity);
        // $this->entityManagerMock->expects($this->exactly(intval($numberOfTimes)))->method("beginTransaction");
        $this->entityManagerMock->expects($this->exactly(intval($numberOfTimes)))->method("commit");

        $this->fileReaderMock = new FileReader();
        $this->dataExporterMock = new LogExtractor();

        $this->runService(self::FILEPATH, $batchSize);
    }

    public function runService($filepath = self::FILEPATH, int $batchSize = 5): bool
    {
        $property = new ReflectionProperty(ImportLog::class, "batchSize");
        $property->setAccessible(true);

        $serviceUnderTest = new ImportLog(
            $this->entityManagerMock,
            $this->fileReaderMock,
            $this->dataExporterMock,
            $this->logFileLib,
            $this->transactionLogLibMock,
            $this->transactionLogRepoMock,
        );
        $property->setValue($serviceUnderTest, $batchSize); // set batchSize

        /** @var ProgressBarInterface */
        $progressBarMock = $this->getMockBuilder(ProgressBarInterface::class)->getMock();

        return $serviceUnderTest->exec($filepath, $progressBarMock);
    }
}
