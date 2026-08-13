<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ProjectUser;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\ProjectService;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AddProjectUserProcessor implements ProcessorInterface
{
    private EntityManagerInterface $em;
    private ProjectService $service;
    private Security $security;

    public function __construct(
        EntityManagerInterface                 $em,
        ProjectService                         $service,
        private readonly UserRepository        $userRepository,
        private readonly ProjectRepository     $projectRepository,
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

        $project = $this->projectRepository->findOneBy(['id' => $uriVariables['id']]);
        if (!$project) {
            throw new \LogicException('No project with id ' . $uriVariables['id']);
        }

        $userToAdd = $this->userRepository->findOneBy(['username' => $data->username]);
        if (!$userToAdd) {
            throw new \LogicException('User doesn\'t exist.');
        }
        if ($this->projectUserRepository->findOneBy(['project' => $project, 'user' => $userToAdd])) {
            throw new \LogicException('User already in project.');
        }

        $this->service->addUser($project, $userToAdd);

        $this->em->flush();
    }
}
