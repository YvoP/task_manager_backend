<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    function addUser(Project $project, User $user)
    {
        $projectUser = new ProjectUser();
        $projectUser->setProject($project)
            ->setUser($user)
            ->setJoinedAt(new \DateTimeImmutable());

        return $projectUser;
    }
}
