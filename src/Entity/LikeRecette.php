<?php

namespace App\Entity;

use App\Repository\LikeRecetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRecetteRepository::class)]
class LikeRecette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer',name:'id_like_recette')]
    private ?int $idLikeRecette = null;

    #[ORM\Column(type:'boolean',nullable: true)]
    private ?bool $booleanLike = null;

    #[ORM\ManyToOne(inversedBy: 'likeRecettes')]
    #[ORM\JoinColumn(name: "recette_id", referencedColumnName: "id_recette")]

    private ?Recette $recette = null;

    #[ORM\ManyToOne(inversedBy: 'likeRecettes')]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id_utilisateur")]

    private ?Utilisateur $utilisateur = null;

    public function getIdLikeRecette(): ?int
    {
        return $this->idLikeRecette;
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
}
