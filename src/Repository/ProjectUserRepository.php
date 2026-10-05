<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectUser>
 */
class ProjectUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectUser::class);
    }

    public function findUnstartedChats(Project $project, User $user)
    {
        return $this->createQueryBuilder('pu')
            ->leftJoin('pu.chats', 'c')
            ->andWhere('pu.project = :project')
            ->andWhere('pu.user != :user')
            ->andWhere('c.id IS NULL')
            ->setParameter('project', $project)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function isAdmin(Project $project, User $user): bool
    {
        $projectUser = $this->createQueryBuilder('pu')
            ->andWhere('pu.project = :project')
            ->andWhere('pu.user = :user')
            ->setParameter('project', $project)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();

        return $projectUser !== null
            && in_array('ROLE_ADMIN', $projectUser->getPermissions(), true);
    }


}
