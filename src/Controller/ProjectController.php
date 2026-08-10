<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\Task;
use App\Entity\TaskContent;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/{id:project}', name: 'app_project_')]
final class ProjectController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    #[Route(path: ['fr' => '/taches', '/tasks'], name: 'tasks')]
    public function tasks(Project $project, UserRepository $userRepository, TaskRepository $taskRepository, ProjectRepository $projectRepository): Response
    {
        $loggedUser = $this->getUser();
        if ($loggedUser === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['username' => $loggedUser->getUserIdentifier()]);
        $tasks = $taskRepository->findTasksByProjectId($project->getId());

        //dd($tasks);

        return $this->render('project/tasks.html.twig', [
            'user' => $user,
            'tasks' => $tasks,
            'project' => $project,
            'statuses' => TaskContent::STATUSES,
        ]);
    }

    #[Route(path: ['fr' => '/taches/ajouter', '/tasks/new'], name: 'new_task')]
    public function addTask(Project $project, Request $request, TaskService $taskService, UserRepository $userRepository, ProjectUserRepository $projectUserRepository): Response
    {
        $loggedUser = $this->getUser();
        if ($loggedUser === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['username' => $loggedUser->getUserIdentifier()]);

        $projectUser = $projectUserRepository->findOneBy([
            'user' => $user,
            'project' => $project,
        ]);

        return $this->handleForm($taskService, $projectUser, $project, new TaskContent(), $request);
    }

    #[Route(path: ['fr' => '/taches/modifier/{taskId:task.id}', '/tasks/edit/{taskId:task.id}'], name: 'edit_task')]
    public function editTask(Project $project, Task $task, Request $request, TaskService $taskService, UserRepository $userRepository, ProjectUserRepository $projectUserRepository): Response
    {
        $loggedUser = $this->getUser();
        if ($loggedUser === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['username' => $loggedUser->getUserIdentifier()]);

        $projectUser = $projectUserRepository->findOneBy([
            'user' => $user,
            'project' => $project,
        ]);

        return $this->handleForm($taskService, $projectUser, $project, $task->getCurrentContent(), $request, $task);
    }

    private function handleForm(
        TaskService $taskService,
        ProjectUser $projectUser,
        Project     $project,
        TaskContent $taskContent,
        Request     $request,
        Task        $task = null
    ): Response
    {
        $form = $this->createForm(TaskType::class, $taskContent, [
            'project' => $project,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($taskService->persistTask($taskContent, $projectUser, $project, $task)) {
                if ($task !== null) {
                    $this->addFlash('success', $this->translator->trans('alert.updated', [], 'tasks'));
                } else {
                    $this->addFlash('success', $this->translator->trans('alert.created', [], 'tasks'));
                }
            } else {
                $this->addFlash('danger', $this->translator->trans('alert.error'));
            }

            return $this->redirectToRoute('app_project_tasks', ['id' => $project->getId()]);
        }

        return $this->render('project/taskForm.html.twig', [
            'form' => $form,
            'taskContent' => $taskContent,
            'task' => $task,
        ]);
    }
}
