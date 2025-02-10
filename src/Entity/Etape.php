<?php

namespace App\Entity;

use App\Repository\EtapeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtapeRepository::class)]
class Etape
{
    private const MIN_LENGTH = 10;
    private const MAX_LENGTH = 250;

    // === ATTRIBUTS PRINCIPAUX ===
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_etape')]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'L\'étape ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[\p{L}\s\-,.]+$/u',
        message: 'Seules les lettres (y compris les accents), espaces, virgules et points sont autorisés.'
    )]
    #[Assert\Length(
        min: self::MIN_LENGTH,
        max: self::MAX_LENGTH,
        minMessage: 'Vous devez entrer au moins {{ limit }} caractères.',
        maxMessage: 'Vous ne pouvez pas dépasser {{ limit }} caractères.'
    )]
    private ?string $etape = null;

    // === RELATION AVEC RECETTE ===
    #[ORM\ManyToOne(inversedBy: 'etapes')]
    #[ORM\JoinColumn(name: "recette_id", referencedColumnName: "id_recette", nullable: false)]
    private ?Recette $recette = null;

    // === GETTERS & SETTERS ===
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtape(): ?string
    {
        return $this->etape;
    }

    public function setEtape(?string $etape): static
    {
        $this->etape = $etape;
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
}
