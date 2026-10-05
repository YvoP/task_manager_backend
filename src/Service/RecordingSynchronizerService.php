<?php

namespace App\Service;

use App\Entity\Meeting;
use App\Entity\Project;
use App\Enum\Status;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class RecordingSynchronizerService
{
    public function __construct(
        private CraigService $craig,
        private EntityManagerInterface $em,
    ) {}

    public function sync(): void
    {
        $recordings = $this->craig->getRecordings();

        foreach ($recordings as $recording) {
            // Find by Craig's ID
            $existing = $this->em
                ->getRepository(Meeting::class)
                ->findOneBy([
                    'craigId' => $recording['id'],
                ]);

            if ($existing) {
                continue;
            }

            $existing = new Meeting();
            $existing->setCraigId($recording['id']);

            $project = $this->em
                ->getRepository(Project::class)
                ->findOneBy(['discordServer' => $recording['info']['guild'], 'discordChannel' => $recording['info']['channel']]);
            if ($project) {
                $existing->setProject($project);
            }

            $existing->setRecordedAt(new DateTimeImmutable($recording['info']['startTime']))
                ->setDuration($this->craig->getDuration($recording['id'], $recording['info']['key']))
                ->setCraigKey($recording['info']['key'])
                ->setName('Meeting_' . $recording['info']['startTime'])
                ->setStatus(Status::PENDING);

            $this->em->persist($existing);
        }

        $this->em->flush();
    }
}
