<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\TranscriptionInputDto;
use App\Entity\Meeting;
use App\Enum\Process;
use App\Enum\Status;
use App\Repository\MeetingRepository;
use App\Service\CraigService;
use Doctrine\ORM\EntityManagerInterface;

class TranscriptionProcessor implements ProcessorInterface
{
    public function __construct(
        private MeetingRepository $meetingRepository,
        private EntityManagerInterface $entityManager,
        private CraigService $craigService,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Meeting {
        /** @var TranscriptionInputDto $data */

        $craigId = $uriVariables['craigId'] ?? null;

        if (!$craigId) {
            throw new \RuntimeException('No craigId supplied in URI');
        }

        $meeting = $this->meetingRepository->findOneBy([
            'craigId' => $craigId,
        ]);

        if (!$meeting) {
            throw new \RuntimeException(
                sprintf('Meeting not found for craigId "%s"', $craigId)
            );
        }

        $meeting->setTranscript($data->transcript);

        $meeting->setStatus(Status::READY);
        $meeting->setActiveProcess(null);

        $audioFile = $this->craigService->downloadAudio($meeting->getCraigId(), $meeting->getCraigKey());

        $meeting->setAudio($audioFile);

        $this->entityManager->flush();

        return $meeting;
    }
}
