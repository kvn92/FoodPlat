<?php

namespace App\Repository;

use App\Entity\Ingredient;
use App\Service\RepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{

    private RepositoryService $repositoryService;

    public function __construct(ManagerRegistry $registry, RepositoryService $repositoryService)
    {
        parent::__construct($registry, Ingredient::class);
        $this->repositoryService = $repositoryService;
    }


      /**
     * Compte le nombre total de Viande.
     *
     * @return int
     */
    public function countIngredient(): int
    {
        return $this->repositoryService->countEntities(Ingredient::class, 'i','id');    
    }


//    /**
//     * @return Ingredient[] Returns an array of Ingredient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ingredient
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
