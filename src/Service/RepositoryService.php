<?php 

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class RepositoryService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function countEntities(string $entityClass, string $alias='e'): int
    {
        return (int) $this->entityManager->createQueryBuilder()
            ->select("COUNT($alias.id)")
            ->from($entityClass, $alias)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
