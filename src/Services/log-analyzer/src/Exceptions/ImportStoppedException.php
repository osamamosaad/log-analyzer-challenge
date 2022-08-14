<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Exceptions;

use Exception;
use Throwable;

class ImportStoppedException extends Exception implements Throwable
{
    private const ERR_CODE = 100;

    public function __construct(string $message = "", ?Throwable $previous = null)
    {
        parent::__construct(
            $message . " Error-message: {$previous->getMessage()} ErrorLine: {$previous->getLine()}",
            self::ERR_CODE,
            $previous
        );
    }
}
