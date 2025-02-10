<?php

namespace App\Entity;

use App\Repository\LikeRecetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRecetteRepository::class)]
class LikeRecette
{
    // === ATTRIBUTS PRINCIPAUX ===
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_like_recette')]
    private ?int $id = null;

    // === RELATIONS ===
    #[ORM\ManyToOne(inversedBy: 'likeRecettes')]
    #[ORM\JoinColumn(name: "recette_id", referencedColumnName: "id_recette", nullable: false)]
    private ?Recette $recette = null;

    #[ORM\ManyToOne(inversedBy: 'likeRecettes')]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id_utilisateur", nullable: false)]
    private ?Utilisateur $utilisateur = null;

    // === ATTRIBUTS SUPPLÉMENTAIRES ===
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $booleanLike = null;

    // === GETTERS & SETTERS ===
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): static
    {
        $this->recette = $recette;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function isBooleanLike(): ?bool
    {
        return $this->booleanLike;
    }

    public function setBooleanLike(?bool $booleanLike): static
    {
        $this->booleanLike = $booleanLike;
        return $this;
    }
}
