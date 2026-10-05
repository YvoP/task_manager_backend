<?php

namespace App\Service;

use App\Entity\Meeting;
use App\Enum\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MeetingTranscriberService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HttpClientInterface $httpClient,
        private string $transcriberUrl,
    ) {

    }

    public function transcribe(Meeting $meeting): void
    {

        if ($meeting->getTranscript()) {
            return;
        }

        $meeting->setStatus(Status::PROCESSING);
        $this->entityManager->flush();

        try {
            $response = $this->httpClient->request(
                'POST',
                $this->transcriberUrl . '/transcribe',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'id' => $meeting->getCraigId(),
                        'key' => $meeting->getCraigKey(),
                    ],
                ]
            );
        } catch (TransportExceptionInterface $e) {
            $meeting->setStatus(Status::PENDING);
            $this->entityManager->flush();
            throw $e;
        }

        if ($response->getStatusCode() >= 400) {
            throw new \RuntimeException(
                'Transcription service returned HTTP ' .
                $response->getStatusCode()
            );
        }
    }
}
