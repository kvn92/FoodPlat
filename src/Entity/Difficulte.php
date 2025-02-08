<?php

namespace App\Entity;

use App\Repository\DifficulteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

#[UniqueEntity('difficulte',message:'{{ value }} est déjà enregistré')]

#[ORM\Entity(repositoryClass: DifficulteRepository::class)]
class Difficulte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer',name:'id_difficulte', unique:true)]
    private ?int $id = null;

    #[ORM\Column(type:'string',length: 50,unique:true)]
    #[NotBlank(message:'Obligatoire')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre mot doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre mot ne peut pas contenir plus de {{ limit }} caractères',
    )]
    private ?string $difficulte = null;

    #[ORM\Column(type:'boolean')]
    private ?bool $statutDifficulte = false;

    /**
     * @var Collection<int, Recette>
     */
    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'difficulte')]
    private Collection $recettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDifficulte(): ?string
    {
        return $this->difficulte;
    }

    public function setDifficulte(string $difficulte): static
    {
        $this->difficulte = strtolower($difficulte); 

        return $this;
    }

    public function isStatutDifficulte(): ?bool
    {
        return $this->statutDifficulte;
    }

    public function setStatutDifficulte(bool $statutDifficulte): static
    {
        $this->statutDifficulte = $statutDifficulte;

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
            $recette->setDifficulte($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getDifficulte() === $this) {
                $recette->setDifficulte(null);
            }
        }

        return $this;
    }
}
