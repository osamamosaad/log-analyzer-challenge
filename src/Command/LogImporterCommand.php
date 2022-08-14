<?php

namespace App\Command;

use App\Services\LogAnalyzer\Application\Command\ImportLog;

use Symfony\Component\Console\{
    Command\Command,
    Attribute\AsCommand,
    Input\InputArgument,
    Input\InputInterface,
    Output\OutputInterface,
};
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'app:log-importer',
    description: 'To import log file',
)]
class LogImporterCommand extends Command
{
    public function __construct(
        private ImportLog $importService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp(
            "The app:log-importer command import and stores log data into DB.
            bin/console app:log-importer <file path>"
        )
            ->addArgument("file", InputArgument::REQUIRED, "Pass file path");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');
        $output->writeln(sprintf('Start to Import, %s', $filePath));

        $progressBar = new ProgressBar($output);
        $this->importService->exec($filePath, $progressBar);
        $output->writeln('Done');

        return Command::SUCCESS;
    }
}
