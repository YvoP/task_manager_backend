<?php

namespace App\Command;

use App\Service\RecordingSynchronizerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:SyncMeetingRecordings',
    description: 'Synchronize recordings from craig to the app db',
)]
class SyncMeetingRecordingsCommand extends Command
{
    public function __construct(private RecordingSynchronizerService $synchronizerService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->synchronizerService->sync();

        $io->success('Recording synchronized successfully!');

        return Command::SUCCESS;
    }
}
