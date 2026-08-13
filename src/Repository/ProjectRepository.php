<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findByUser(int $user): array
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->join('p.projectUsers', 'pu')
            ->where('pu.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
