<?php

namespace App\Twig\Runtime;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ChatRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class ProjectExtensionRuntime implements RuntimeExtensionInterface
{
    private ProjectRepository $projectRepository;
    private ChatRepository $chatRepository;
    private ProjectUserRepository $projectUserRepository;

    public function __construct(ProjectRepository $projectRepository, private TranslatorInterface $translator, ChatRepository $chatRepository, ProjectUserRepository $projectUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
        $this->chatRepository = $chatRepository;
        $this->projectRepository = $projectRepository;
    }

    public function getProjects(User $user)
    {
        return $this->projectRepository->findByUser($user->getId());
    }

    public function getChats(Project $project, User $user)
    {
        return $this->chatRepository->findByProjectAndUser($project->getId(), $user->getId());
    }

    public function getUnstartedChats(Project $project, User $user)
    {
        return $this->projectUserRepository->findUnstartedChats($project, $user);
    }

    public function isAdmin(Project $project, User $user): bool
    {
        return $this->projectUserRepository->isAdmin($project, $user);
    }

    public function getTimeDiff(\DateTimeInterface $date): string
    {
        $diff = $date->diff(new \DateTimeImmutable());

        if ($diff->y > 0) {
            return $this->translator->trans('relative_date.years_later', ['%count%' => $diff->y], 'tasks');
        }

        if ($diff->m > 0) {
            return $this->translator->trans('relative_date.months_later', ['%count%' => $diff->m], 'tasks');
        }

        if ($diff->days > 0) {
            return $this->translator->trans('relative_date.days_later', ['%count%' => $diff->days], 'tasks');
        }

        return $this->translator->trans('relative_date.today', [], 'tasks');
    }

    public function formatAudioTime(float $seconds): string
    {
        $minutes = floor($seconds / 60);
        $seconds = floor($seconds % 60);

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
