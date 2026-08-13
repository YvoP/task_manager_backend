<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    function addUser(Project $project, User $user, ?bool $isAdmin = false): bool
    {
        try {
            $projectUser = new ProjectUser();
            $projectUser->setProject($project)
                ->setUser($user)
                ->setJoinedAt(new \DateTimeImmutable());

            if ($isAdmin) {
                $projectUser->setPermissions(["ROLE_ADMIN"]);
            }

            $this->em->persist($projectUser);

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    function persistProject(Project $project, ?User $user = null): bool
    {
        try {
            if ($user !== null) {
                $project->setCreatedAt(new \DateTimeImmutable());
                $this->addUser($project, $user, true);
                $this->em->persist($project);
            }
            $this->em->flush();
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
}
