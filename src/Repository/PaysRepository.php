<?php

namespace App\Repository;

use App\Entity\Pays;
use App\Service\RepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pays>
 */
class PaysRepository extends ServiceEntityRepository
{
    private RepositoryService $repositoryService;
    public function __construct(ManagerRegistry $registry,RepositoryService $repositoryService)
    {
        parent::__construct($registry, Pays::class);
        $this->repositoryService = $repositoryService;
    }

        /**
     * Compte le nombre total de catégories.
     *
     * @return int
     */
    public function countPays(): int
    {
        return $this->repositoryService->countEntities(Pays::class, 'p','id');    
    }


//    /**
//     * @return Pays[] Returns an array of Pays objects
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

//    public function findOneBySomeField($value): ?Pays
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
