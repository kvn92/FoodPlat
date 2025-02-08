<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
#[UniqueEntity('nomPays', message: '{{ value }} est déjà enregistré')]
class Pays
{

    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 30;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer", name:'id_pays')]
    private ?int $id = null;

    #[ORM\Column(type:'string',length: 30, unique:true)]
    #[NotBlank(message: 'Le nom ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[-\p{L}]+$/u',  // ✅ "-" placé au début,
        message: 'Seules les lettres (y compris les accents) sont autorisées.'
    )]
    #[Assert\Length(
        min: self::MIN_LENGTH,
        max: self::MAX_LENGTH,
        minMessage: 'Vous devez entrer au moins {{ limit }} caractères.',
        maxMessage: 'Vous ne pouvez pas dépasser {{ limit }} caractères.'
        )]
    private ?string $nomPays = null;

    #[ORM\Column(type:'boolean')]
    private ?bool $statutPays = false;

    /**
     * @var Collection<int, Recette>
     */
    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'pays')]
    private Collection $recettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPays(): ?string
    {
        return $this->nomPays;
    }

    public function setNomPays(string $nomPays): static
    {
        $this->nomPays = trim(strtolower($nomPays));

        return $this;
    }

    public function isStatutPays(): ?bool
    {
        return $this->statutPays;
    }

    public function setStatutPays(bool $statutPays): static
    {
        $this->statutPays = $statutPays;

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->setPays($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getPays() === $this) {
                $recette->setPays(null);
            }
        }

        return $this;
    }
}
