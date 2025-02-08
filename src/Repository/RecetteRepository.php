<?php

namespace App\Repository;

use App\Entity\Recette;
use App\Service\RepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 */
class RecetteRepository extends ServiceEntityRepository
{
    private RepositoryService $repositoryService;

    
    public function __construct(ManagerRegistry $registry, RepositoryService $repositoryService)
    {
        parent::__construct($registry, Recette::class);
        $this->repositoryService = $repositoryService;
    }

    /**
     * Compte le nombre total de catégories.
     *
     * @return int
     */
    public function countRecette(){
        return $this->repositoryService->countEntities(Recette::class, 'r','id');    
    }

//    /**
//     * @return Recette[] Returns an array of Recette objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recette
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
