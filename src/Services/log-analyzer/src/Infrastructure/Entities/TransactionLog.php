<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Infrastructure\Entities;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{
    Entity,
    Id,
    Column,
    Table,
    GeneratedValue,
    JoinColumn,
    ManyToOne
};

enum TransactionLogMethod: string
{
    case GET        = 'GET';
    case POST       = 'POST';
    case PUT        = 'PUT';
    case PATCH      = 'PATCH';
    case DELETE     = 'DELETE';
    case HEAD       = 'HEAD';
    case TRACE      = 'TRACE';
    case CONNECT    = 'CONNECT';
    case OPTIONS    = 'OPTIONS';
}

#[Entity(), Table(name: 'transaction_log')]
class TransactionLog
{
    #[Column, Id, GeneratedValue]
    private ?int $id;

    #[ManyToOne(targetEntity: LogFile::class), JoinColumn(name: "log_file_id", referencedColumnName: "id")]
    public LogFile $logFile;

    #[Column(name: "line_num", type: Types::INTEGER)]
    public int $lineNum;

    #[Column(name: "service_name", type: Types::STRING)]
    public string $serviceName;

    #[Column(name: "endpoint", type: Types::STRING, length: 255)]
    public string $endpoint;

    #[Column(name: "method", type: Types::STRING, enumType: TransactionLogMethod::class)]
    public TransactionLogMethod $method;

    #[Column(name: "status_code", type: Types::INTEGER)]
    public int $statusCode;

    #[Column(name: "http_version", type: Types::STRING)]
    public string $httpVersion;

    #[Column(name: "log_date", type: Types::DATETIME_MUTABLE)]
    public $logDate;

    #[Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    public $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setLineNum(int $lineNum): self
    {
        $this->lineNum = $lineNum;
        return $this;
    }

    public function setLogFile(LogFile $logFile): self
    {
        $this->logFile = $logFile;
        return $this;
    }

    public function getLogFileId(): LogFile
    {
        return $this->logFile;
    }

    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setMethod(TransactionLogMethod $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): TransactionLogMethod
    {
        return $this->method;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setHttpVersion(string $httpVersion): self
    {
        $this->httpVersion = $httpVersion;
        return $this;
    }

    public function getHttpVersion(): string
    {
        return $this->httpVersion;
    }

    public function setLogDate(DateTimeInterface $logDate): self
    {
        $this->logDate = $logDate;
        return $this;
    }

    public function getlogDate(): DateTimeInterface
    {
        return $this->logDate;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
