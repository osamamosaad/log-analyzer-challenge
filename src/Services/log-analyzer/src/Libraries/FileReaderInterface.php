<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries;

interface FileReaderInterface
{
    public function open($filePath): self;

    public function totalNumberOFLines(): int;

    public function getSize(): int;

    public function lineNumber(): int;

    public function next(): bool;

    public function currentLine(): string;

    public function startFrom(int $lineNumber): self;

    public function close(): void;
}
