<?php

namespace App\Repository;

use App\Entity\Viande;
use App\Service\RepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Viande>
 */
class ViandeRepository extends ServiceEntityRepository
{

    private RepositoryService $repositoryService;

    public function __construct(ManagerRegistry $registry, RepositoryService $repositoryService)
    {
        parent::__construct($registry, Viande::class);
        $this->repositoryService = $repositoryService;
    }

  /**
     * Compte le nombre total de Viande.
     *
     * @return int
     */
    public function countViande(): int
    {
        return $this->repositoryService->countEntities(Viande::class, 'v','id');    
    }


//    /**
//     * @return Viande[] Returns an array of Viande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Viande
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
