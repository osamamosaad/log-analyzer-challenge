<?php

declare(strict_types=1);

use App\Services\LogAnalyzer\Application\Query\GetCountTransactionLogs;
use App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces\TransactionLogRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetCountTransactionLogsTest extends TestCase
{
    private $mockRepo;
    protected function setUp(): void
    {
        /**
         * @var TransactionLogRepositoryInterface
         */
        $this->mockRepo = $this->getMockBuilder(TransactionLogRepositoryInterface::class)->getMock();
        $this->serviceUnderTest = new GetCountTransactionLogs($this->mockRepo);
    }

    public function testfindByFilterInvoced(): void
    {
        $this->mockRepo->expects($this->once())->method("findByFilter");
        $this->serviceUnderTest->get([]);
    }
}
