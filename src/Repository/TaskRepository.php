<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findTasksByProjectId(string $projectId): array
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'tc', 'pu', 'u')
            ->join('t.currentContent', 'tc')
            ->join('t.project', 'p')
            ->leftJoin('tc.attributedTo', 'pu')
            ->leftJoin('pu.user', 'u')
            ->where('p.id = :projectId')
            ->setParameter('projectId', $projectId)
            ->orderBy('tc.status', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
