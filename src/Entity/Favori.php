<?php

namespace App\Entity;

use App\Repository\FavoriRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriRepository::class)]
class Favori
{
    // === ATTRIBUTS PRINCIPAUX ===
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_favori')]
    private ?int $id = null;

    // === RELATIONS ===
    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'favoris')]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id_utilisateur", nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: Recette::class, inversedBy: "favoris")]
    #[ORM\JoinColumn(name: "recette_id", referencedColumnName: "id_recette", onDelete: "CASCADE", nullable: false)]
    private ?Recette $recette = null;

    // === ATTRIBUT SUPPLÉMENTAIRE ===
    #[ORM\Column(type: 'boolean')]
    private bool $isFavori = true; // Par défaut, favori actif

    // === GETTERS & SETTERS ===
    public function getId(): ?int
    {
        return $this->id;
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

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): static
    {
        $this->recette = $recette;
        return $this;
    }

    public function isFavori(): bool
    {
        return $this->isFavori;
    }

    public function setIsFavori(bool $isFavori): self
    {
        $this->isFavori = $isFavori;
        return $this;
    }
}
