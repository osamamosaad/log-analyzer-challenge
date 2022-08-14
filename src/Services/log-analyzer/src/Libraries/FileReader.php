<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries;

use SplFileObject;

class FileReader implements FileReaderInterface
{
    private ?SplFileObject $file;
    private ?string $currentLine;
    private ?int $lineNumber;

    public function open($filePath): self
    {
        $this->file = new SplFileObject($filePath);
        $this->file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY);
        $this->setTotalNumberOFLines();
        $this->file->seek(0); // Start From the first line
        $this->currentLine = $this->file->current();

        return $this;
    }

    protected function setTotalNumberOFLines()
    {
        $this->file->seek($this->file->getSize());
        // the check to remove the last new line
        $this->totalNumberOFLines = $this->file->current() ? $this->file->key() : $this->file->key() - 1;
    }

    public function totalNumberOFLines(): int
    {
        return $this->totalNumberOFLines;
    }

    public function getSize(): int
    {
        return $this->file->getSize();
    }

    public function lineNumber(): int
    {
        return $this->lineNumber;
    }

    public function next(): bool
    {
        if ($this->file->valid() && $this->file->current()) {
            $this->currentLine = $this->file->current();
            $this->lineNumber = $this->file->key();

            $this->file->next();
            return true;
        }
        return false;
    }

    public function currentLine(): string
    {
        return $this->currentLine;
    }

    public function startFrom(int $lineNumber): self
    {
        $this->file->seek($lineNumber);
        return $this;
    }

    public function close(): void
    {
        $this->file = null;
        $this->currentLine = null;
    }

    public function __destruct()
    {
        $this->close();
    }
}
