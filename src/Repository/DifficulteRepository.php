<?php

namespace App\Repository;

use App\Entity\Difficulte;
use App\Service\RepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Difficulte>
 */
class DifficulteRepository extends ServiceEntityRepository
{

    private RepositoryService $repositoryService;


    public function __construct(ManagerRegistry $registry,  RepositoryService $repositoryService)
    {
        parent::__construct($registry, Difficulte::class);
        $this->repositoryService = $repositoryService;

    }

/**
     * Compte le nombre total de catégories.
     *
     * @return int
     */
    public function countDifficulte(){
        return $this->repositoryService->countEntities(Difficulte::class, 'd','id');    
    }
    

//    /**
//     * @return Difficulte[] Returns an array of Difficulte objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Difficulte
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
