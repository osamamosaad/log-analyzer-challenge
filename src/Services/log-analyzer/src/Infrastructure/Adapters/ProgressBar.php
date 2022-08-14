<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Infrastructure\Adapters;

use Symfony\Component\Console\Helper\ProgressBar as HelperProgressBar;

class ProgressBar implements ProgressBarInterface
{
    public function __construct(private HelperProgressBar $progressBar)
    {
    }

    public function setMaxSteps($maxSteps)
    {
        $this->progressBar->setMaxSteps($maxSteps);
    }

    public function advance()
    {
        $this->progressBar->advance();
    }
}
