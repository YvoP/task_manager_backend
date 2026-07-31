<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use App\Repository\ProjectRepository;
use Twig\Extension\RuntimeExtensionInterface;

class ProjectExtensionRuntime implements RuntimeExtensionInterface
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getProjects(User $user)
    {
        return $this->projectRepository->findByUser($user);
    }
}
