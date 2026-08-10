<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\ProjectUserRepository;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CreateTaskRevisionProcessor implements ProcessorInterface
{
    private EntityManagerInterface $em;
    private TaskService $service;
    private Security $security;

    public function __construct(
        EntityManagerInterface                 $em,
        TaskService                            $service,
        private readonly TaskRepository        $taskRepository,
        private readonly ProjectUserRepository $projectUserRepository,
        Security                               $security,
    )
    {
        $this->security = $security;
        $this->service = $service;
        $this->em = $em;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \LogicException('No authenticated user.');
        }

        $task = $this->taskRepository->find($uriVariables['id']);

        $projectUser = $this->projectUserRepository->findOneBy([
            'user' => $user,
            'project' => $task->getProject(),
        ]);

        $revision = $this->service->createRevision($task, $data, $projectUser);

        if (!$task) {
            throw new \RuntimeException('Task not found');
        }

        $this->em->persist($revision);
        $this->em->flush();
    }
}
