<?php

namespace App\Repository;

use App\Entity\TaskContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskContent>
 */
class TaskContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskContent::class);
    }

    public function findContentsByProjectId(string $projectId): array
    {
        $sub = $this->createQueryBuilder('tc2')
            ->select('MAX(tc2.id)')
            ->where('tc2.task = tc.task');

        return $this->createQueryBuilder('tc')
            ->join('tc.task', 't')
            ->join('t.project', 'p')
            ->where('p.id = :projectId')
            ->andWhere('tc.id = (' . $sub->getDQL() . ')')
            ->setParameter('projectId', $projectId)
            ->orderBy('tc.status', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?TaskContent
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
