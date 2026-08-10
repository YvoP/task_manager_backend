<?php

namespace App\Repository;

use App\Entity\ProjectUser;
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

    //    /**
    //     * @return AddProjectUserProcessor[] Returns an array of AddProjectUserProcessor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AddProjectUserProcessor
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findUnstartedChats(int $projectId, int $userId)
    {
        return $this->createQueryBuilder('pu')
            ->leftJoin('pu.chats', 'c')
            ->andWhere('pu.project = :project')
            ->andWhere('pu.user != :user')
            ->andWhere('c.id IS NULL')
            ->setParameter('project', $projectId)
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult();
    }


}
