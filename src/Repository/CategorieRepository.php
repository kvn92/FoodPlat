<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Service\RepositoryService;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{ 
    private RepositoryService $repositoryService;
 
    public function __construct(ManagerRegistry $registry, RepositoryService $repositoryService)
    {
        parent::__construct($registry, Categorie::class);
        $this->repositoryService = $repositoryService;
    }



    /**
     * Compte le nombre total de catégories.
     *
     * @return int
     */
    public function countCategories(): int
    {
        return $this->repositoryService->countEntities(Categorie::class, 'c','id');    
    }



     /**
     * Compte le nombre de recettes dans une catégorie donnée
     *
     * @param int $categorieId L'identifiant de la catégorie
     * @return int Le nombre de recettes
     */
    public function countRecettesInCategorie(int $categorieId): int
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(r.id)')
            ->leftJoin('c.recettes', 'r') // Liaison avec l'entité Recette
            ->where('c.id = :categorieId')
            ->setParameter('categorieId', $categorieId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Categorie[] Returns an array of Categorie objects
//     */

//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Categorie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
