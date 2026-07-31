<?php

namespace App\Controller;

use App\Entity\TaskContent;
use App\Repository\TaskContentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/{projectId}')]
final class ProjectController extends AbstractController
{
    #[Route(path: ['fr' => '/taches', '/tasks'], name: 'app_project_tasks')]
    public function tasks(string $projectId, UserRepository $userRepository, TaskContentRepository $taskContentRepository): Response
    {
        $loggedUser = $this->getUser();
        if ($loggedUser === null)
        {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['username' => $loggedUser->getUserIdentifier()]);
        $tasks = $taskContentRepository->findContentsByProjectId($projectId);

        return $this->render('project/tasks.html.twig', [
            'user' => $user,
            'tasks' => $tasks,
            'statuses' => TaskContent::STATUSES,
        ]);
    }
}
