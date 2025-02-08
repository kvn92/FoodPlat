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

    public function countEntities(string $entityClass, string $alias='e', string $a): int
    {

       // $allowedFields = ['id', 'name', 'createdAt', 'updatedAt']; // Définir les champs autorisés
      //  if (!in_array($a, $allowedFields, true)) {
     //       throw new \InvalidArgumentException("Champ '$a' non valide !");
      //  }
    

        return (int) $this->entityManager->createQueryBuilder()
            ->select("COUNT($alias.$a)")
            ->from($entityClass, $alias)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
