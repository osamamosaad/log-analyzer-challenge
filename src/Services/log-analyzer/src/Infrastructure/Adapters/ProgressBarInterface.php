<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Infrastructure\Adapters;

interface ProgressBarInterface
{
    public function setMaxSteps($maxSteps);
    public function advance();
}
