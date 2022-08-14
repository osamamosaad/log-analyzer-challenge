<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Infrastructure\Entities;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column, Entity, Table, Id, GeneratedValue};

enum LogFileStatus: string
{
    case InProgress = 'in-progress';
    case Stopped    = 'stopped';
    case Done       = 'done';
}

#[Entity(), Table(name: 'log_file')]
class LogFile
{
    #[Column, Id, GeneratedValue]
    private ?int $id;

    #[Column(name: "file_name", type: Types::STRING)]
    public $fileName;

    #[Column(name: "unique_name", type: Types::STRING, length: 32)]
    public $uniqueName;

    #[Column(name: "status", type: Types::STRING, enumType: LogFileStatus::class)]
    public $status;

    #[Column(name: "last_line", type: Types::INTEGER)]
    public $lastLine;

    #[Column(name: "total_lines", type: Types::INTEGER)]
    public $totalLines;

    #[Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    public $createdAt;

    #[Column(name: "updated_at", type: Types::DATETIME_MUTABLE)]
    public $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUniqueName($uniqueName): self
    {
        $this->uniqueName = $uniqueName;
        return $this;
    }

    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }

    public function setFileName($fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setTotalLines(int $totalLines): self
    {
        $this->totalLines = $totalLines;
        return $this;
    }

    public function getTotalLines(): int
    {
        return $this->totalLines;
    }

    public function setStatus(LogFileStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): LogFileStatus
    {
        return $this->status;
    }

    public function setLastLine(int $lastLine): self
    {
        $this->lastLine = $lastLine;
        return $this;
    }

    public function getLastLine(): int
    {
        return $this->lastLine;
    }

    public function setCreatedAt(DateTimeInterface $date): self
    {
        $this->createdAt = $date;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(DateTimeInterface $date): self
    {
        $this->updatedAt = $date;
        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
