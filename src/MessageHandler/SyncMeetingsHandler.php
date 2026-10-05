<?php

namespace App\MessageHandler;

use App\Message\SyncMeetings;
use App\Service\RecordingSynchronizerService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SyncMeetingsHandler
{
    public function __construct(private RecordingSynchronizerService $synchronizerService)
    { }

    public function __invoke(SyncMeetings $message): void
    {
        $this->synchronizerService->sync();
    }
}
