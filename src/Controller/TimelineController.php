<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: ['fr' => '/chronologie', '/timeline'], name: 'app_timeline')]
final class TimelineController extends AbstractController
{
    #[Route('/{id:project}', name: '_project')]
    public function index(Project $project, UserRepository $userRepository, TaskRepository $taskRepository): Response
    {
        $loggedUser = $this->getUser();
        if ($loggedUser === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['username' => $loggedUser->getUserIdentifier()]);
        $tasks = $taskRepository->findTasksByProjectId($project->getId());

        $timelineTasks = [];

        foreach ($tasks as $task) {
            $timelineTasks[] = [
                'id' => $task->getId(),
                'title' => $task->getCurrentContent()->getTitle(),
                'start' => ($task->getCurrentContent()->getStartDate() ?? $task->getCurrentContent()->getUpdatedAt())?->format('Y-m-d'),
                'end' => $task->getCurrentContent()->getDeadline()?->format('Y-m-d'),
                'profileImage' => $task->getCurrentContent()->getAttributedTo() === null ? '' : $task->getCurrentContent()->getAttributedTo()->getUser()->getProfileImage(),
                'className' => $task->getCurrentContent()->getStartDate() === null ? 'no-start-date' : '',
            ];
        }

        return $this->render('timeline/index.html.twig', [
            'controller_name' => 'TimelineController',
            'user' => $user,
            'project' => $project,
            'tasks' => $timelineTasks,
        ]);
    }
}
