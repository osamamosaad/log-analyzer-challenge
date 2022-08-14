<?php

declare(strict_types=1);

use App\Services\LogAnalyzer\Infrastructure\Entities\LogFile as LogFileEntity;
use App\Services\LogAnalyzer\Libraries\LogFile;
use App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces\LogFileRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class LogFileTest extends TestCase
{
    private LogFile $serviceUnderTest;

    protected function setUp(): void
    {
        /**
         * @var LogFileRepositoryInterface
         */
        $this->mockRepo = $this->getMockBuilder(LogFileRepositoryInterface::class)->getMock();
        $this->serviceUnderTest = new LogFile($this->mockRepo);
    }

    public function testGetByUniqueNameInvoked(): void
    {
        $this->mockRepo->expects($this->once())->method("getByUniqueName");
        $logFile = $this->runService();
    }

    public function testSaveMethodInvoced(): void
    {
        $this->mockRepo->expects($this->once())->method("save");
        $this->runService();
    }

    public function testReturnValueOfTypeLogFileEntity(): void
    {
        $logFile = $this->runService();
        $this->assertInstanceOf(LogFileEntity::class, $logFile);
    }

    protected function runService($filePath = "/path/transaction.log", $totalLines = 100)
    {
        return $this->serviceUnderTest->addIfNotExists($filePath, $totalLines);
    }
}
