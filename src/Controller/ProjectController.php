<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\Task;
use App\Entity\TaskContent;
use App\Entity\User;
use App\Form\ProjectType;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\ProjectService;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '', name: 'app_project_')]
final class ProjectController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator, private readonly UserRepository $userRepository, private ProjectUserRepository $projectUserRepository)
    {
    }

    #[Route(path: ['fr' => '/{id:project}/taches', '/{id:project}/tasks'], name: 'tasks')]
    public function tasks(Project $project, UserRepository $userRepository, TaskRepository $taskRepository): Response
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

    #[Route(path: ['fr' => '/{id:project}/taches/ajouter', '/{id:project}/tasks/new'], name: 'new_task')]
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

    #[Route(path: ['fr' => '/{id:project}/taches/modifier/{taskId:task.id}', '/{id:project}/tasks/edit/{taskId:task.id}'], name: 'edit_task')]
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

    #[Route(path: ['fr' => '/nouveau', '/new'], name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectService $projectService): Response
    {
        if (!$this->isUserLogged()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getBddUser();

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectService->persistProject($project, $user);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route(path: ['fr' => '/{id:project}/modifier', '/{id:project}/edit'], name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, ProjectService $projectService): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        $user = $this->getBddUser();

        if ($form->isSubmitted() && $form->isValid() && $this->isUserProjectAdmin($project, $user)) {
            $projectService->persistProject($project);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route(path: ['fr' => '/{id:project}/supprimer', '/{id:project}/delete'], name: 'delete', methods: ['POST'])]
    public function delete(Project $project, ProjectService $projectService): Response
    {
        $user = $this->getBddUser();

        if ($this->isUserProjectAdmin($project, $user)) {
            $project->setIsArchived(true);
            $projectService->persistProject($project);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_project_tasks', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
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

    private function isUserLogged(): bool
    {
        return $this->getUser() !== null;
    }

    private function getBddUser(): ?User
    {
        return $this->userRepository->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);
    }

    private function isUserInProject(Project $project, User $user): ProjectUser
    {
        return $this->projectUserRepository->findOneBy([
            'user' => $user,
            'project' => $project,
        ]);
    }

    private function isUserProjectAdmin(Project $project, User $user): bool
    {
        $projectUser = $this->isUserInProject($project, $user);
        if (in_array("ROLE_ADMIN", $projectUser->getPermissions()) ) {
            return true;
        }
        return false;
    }
}
