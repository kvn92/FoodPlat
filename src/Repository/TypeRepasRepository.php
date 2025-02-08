<?php

namespace App\Repository;

use App\Entity\TypeRepas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Service\RepositoryService;

use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeRepas>
 */
class TypeRepasRepository extends ServiceEntityRepository
{

    private RepositoryService $repositoryService;

    public function __construct(ManagerRegistry $registry, RepositoryService $repositoryService)
    {
        parent::__construct($registry, TypeRepas::class);
        $this->repositoryService = $repositoryService;

    }

     /**
     * Compte le nombre total de catégories.
     *
     * @return int
     */
    public function countTypeRepas(): int
    {
        return $this->repositoryService->countEntities(TypeRepas::class, 't','id');    
    }

//    /**
//     * @return TypeRepas[] Returns an array of TypeRepas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeRepas
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
