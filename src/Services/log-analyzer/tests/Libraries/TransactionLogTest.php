<?php

declare(strict_types=1);

use App\Services\LogAnalyzer\Infrastructure\{
    Entities\TransactionLogMethod,
    Entities\LogFile as LogFileEntity,
    Entities\TransactionLog as TransactionLogEntity,
};

use App\Services\LogAnalyzer\Libraries\{
    RepositoriesInterfaces\TransactionLogRepositoryInterface,
    TransactionLog
};
use PHPUnit\Framework\TestCase;

final class TransactionLogTest extends TestCase
{
    private TransactionLog $serviceUnderTest;

    protected function setUp(): void
    {
        /**
         * @var TransactionLogRepositoryInterface
         */
        $this->mockRepo = $this->getMockBuilder(TransactionLogRepositoryInterface::class)->getMock();
        $this->serviceUnderTest = new TransactionLog($this->mockRepo);
        $this->transactionLogEntity = new TransactionLogEntity();
    }


    public function testAddMethodReturnTransactionLogEntity(): void
    {
        $entityResult = $this->runService();
        $this->assertInstanceOf(TransactionLogEntity::class, $entityResult);
    }

    public function testSaveMethodInvoced(): void
    {
        $this->mockRepo->expects($this->once())->method("save");
        $this->runService();
    }

    protected function runService()
    {
        return $this->serviceUnderTest->add(
            new LogFileEntity(),
            10,
            "USER-SERVICE",
            "/user",
            TransactionLogMethod::from("POST"),
            200,
            "HTTP/1.1",
            date_create(),
        );
    }
}
