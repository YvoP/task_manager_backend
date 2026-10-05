<?php

namespace App\Service;

use App\Dto\UpdateTaskDto;
use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\Task;
use App\Entity\TaskContent;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function createRevision(Task $task, UpdateTaskDto $dto, ProjectUser $user): TaskContent
    {
        $latest = $task->getCurrentContent();

        // Copy everything
        $new = clone $latest;

        $new->setUpdatedAt(new \DateTimeImmutable())
            ->setModifiedBy($user);

        foreach ($latest->getFiles() as $file) {
            $new->addFile($file);
        }

        // Apply only supplied changes
        if ($dto->title !== null) {
            $new->setTitle($dto->title);
        }

        if ($dto->description !== null) {
            $new->setDescription($dto->description);
        }

        if ($dto->status !== null) {
            $new->setStatus($dto->status);
        }

        if ($dto->priority !== null) {
            $new->setPriority($dto->priority);
        }

        if ($dto->startDate !== null) {
            $new->setStartDate($dto->startDate);
        }

        if ($dto->deadline !== null) {
            $new->setDeadline($dto->deadline);
        }

        if ($dto->isArchived !== null) {
            $new->setIsArchived($dto->isArchived);
        }

        if ($dto->attributedTo !== null) {
            $new->setAttributedTo($dto->attributedTo);
        }

        $task->setCurrentContent($new);

        return $new;
    }

    public function persistTask(TaskContent $taskContent, ProjectUser $user, Project $project, ?Task $task = null): bool
    {
        try {
            if ($task !== null) {
                $new = clone $taskContent;

                $new->setUpdatedAt(new \DateTimeImmutable())
                    ->setModifiedBy($user);
                $task->setCurrentContent($new);

                $this->em->persist($new);
            } else {
                $task = new Task();
                $task->setCreatedBy($user)
                    ->setCurrentContent($taskContent)
                    ->setProject($project)
                    ->setCreatedAt(new \DateTimeImmutable());
                $taskContent->setTask($task)
                    ->setModifiedBy($user)
                    ->setUpdatedAt(new \DateTimeImmutable());

                $this->em->persist($task);
                $this->em->persist($taskContent);
            }

            $this->em->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
