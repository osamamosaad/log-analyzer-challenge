<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Infrastructure\Entities;


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
