<?php 

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ViandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ViandeRepository::class)]
#[UniqueEntity('nomViande', message: '{{ value }} est déjà enregistré.')]
class Viande
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 15;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_viande',unique:true)]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[\p{L}-]+$/u',
        message: 'Seules les lettres (y compris les accents) sont autorisées.'
    )]
    #[Assert\Length(
        min: self::MIN_LENGTH,
        max: self::MAX_LENGTH,
        minMessage: 'Vous devez entrer au moins {{ limit }} caractères.',
        maxMessage: 'Vous ne pouvez pas dépasser {{ limit }} caractères.'
    )]
    private ?string $nomViande = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $statutViande = false;

    /** @var Collection<int, Recette> */
    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'viande', cascade: ['remove'])]    private Collection $recettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
        $this->statutViande = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomViande(): ?string
    {
        return $this->nomViande;
    }

    public function setNomViande(string $nomViande): static
    {
        $this->nomViande = strtolower(trim($nomViande));
        return $this;
    }

    public function isStatutViande(): bool
    {
        return $this->statutViande;
    }

    public function setStatutViande(bool $statutViande): static
    {
        $this->statutViande = $statutViande;
        return $this;
    }

    /** @return Collection<int, Recette> */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->setViande($this);
        }
        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette) && $recette->getViande() === $this) {
            $recette->setViande(null);
        }
        return $this;
    }
}
