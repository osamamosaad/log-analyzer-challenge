<?php

declare(strict_types=1);

use App\Services\LogAnalyzer\Libraries\LogExtractor;
use PHPUnit\Framework\TestCase;

final class LogExtractorTest extends TestCase
{
    private LogExtractor $serviceUnderTest;

    protected function setUp(): void
    {
        $this->serviceUnderTest = new LogExtractor();
    }


    public function testParsLogLine(): void
    {
        $logLine = 'INVOICE-SERVICE - - [17/Aug/2021:09:23:53 +0000] "POST /invoices HTTP/1.1" 201';
        $actual = $this->serviceUnderTest->extract($logLine);

        $expected = [
            "serviceName" => "INVOICE-SERVICE",
            "date" => "17/Aug/2021:09:23:53 +0000",
            "method" => "POST",
            "endpoint" => "/invoices",
            "http" => "HTTP/1.1",
            "status" => "201",
        ];

        foreach ($expected as $key => $expectedValue) {
            $this->assertEquals($expectedValue, $actual[$key]);
        }
    }
}
