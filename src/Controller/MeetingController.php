<?php

namespace App\Controller;

use App\Entity\Meeting;
use App\Entity\Project;
use App\Enum\Process;
use App\Enum\Status;
use App\Repository\MeetingRepository;
use App\Repository\UserRepository;
use App\Service\MeetingTranscriberService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: ['fr' => 'reunions' ,'meetings'], name: 'app_meetings_')]
final class MeetingController extends AbstractController
{
    #[Route('/{id:project}', name: 'index')]
    public function index(Project $project, UserRepository $userRepository, MeetingRepository $meetingRepository): Response
    {
        $loggedUser = $this->getUser();
        if ($loggedUser === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['username' => $loggedUser->getUserIdentifier()]);
        $meetings = $meetingRepository->findBy(['project' => $project]);

        return $this->render('meeting/index.html.twig', [
            'controller_name' => 'MeetingController',
            'user' => $user,
            'project' => $project,
            'meetings' => $meetings,
        ]);
    }

    #[Route(path: ['fr' => '/{id:meeting}/transcrire' ,'/{id:meeting}/transcribe'], name: 'transcribe', methods: ['POST'])]
    public function transcribe(
        Meeting $meeting,
        EntityManagerInterface $entityManager,
        MeetingTranscriberService $meetingTranscriberService
    ): RedirectResponse
    {
        if ($meeting->getStatus() === Status::PROCESSING) {
            return $this->redirectToRoute('app_meetings_index', ['id' => $meeting->getProject()->getId()]);
        }

        $meeting->setStatus(Status::PROCESSING);
        $meeting->setActiveProcess(Process::TRANSCRIBING);

        $meetingTranscriberService->transcribe($meeting);

        $entityManager->flush();

        return $this->redirectToRoute('app_meetings_index', ['id' => $meeting->getProject()->getId()]);
    }

    #[Route(path: ['fr' => '/{id:meeting}/analyze' ,'/{id:meeting}/analyze'], name: 'analyze', methods: ['GET'])]
    public function analyze() {

    }

    #[Route('/{id:project}/{meetingId:meeting.id}', name: 'details')]
    public function details(Project $project, UserRepository $userRepository, Meeting $meeting): Response
    {
        $loggedUser = $this->getUser();
        if ($loggedUser === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['username' => $loggedUser->getUserIdentifier()]);

        return $this->render('meeting/details.html.twig', [
            'user' => $user,
            'project' => $project,
            'meeting' => $meeting,
        ]);
    }
}
