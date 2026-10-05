<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;

class UserProjectsProvider implements ProviderInterface
{

    private ProjectRepository $projectRepository;

    public function __construct(
        ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->projectRepository->findByUser($uriVariables['id']);
    }
}
