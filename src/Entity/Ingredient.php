<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[UniqueEntity('nomIngredient',message:'{{ value }} est déjà enregistré')]

class Ingredient
{

    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 15;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer',name:'id_ingredient')]
    private ?int $id = null;

    #[ORM\Column(type:'string',length: 50,unique:true)]
    #[NotBlank(message: 'Le nom ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[-\p{L}]+$/u',  // ✅ "-" placé au début,
        message: 'Seules les lettres (y compris les accents) sont autorisées.'
    )]    
    #[Assert\Length(
        min: self::MIN_LENGTH,
        max:self::MAX_LENGTH,
        minMessage: 'Vous devez entrer au moins {{ limit }} caractères.',
        maxMessage: 'Vous ne pouvez pas dépasser {{ limit }} caractères.'
        )]  

    private ?string $nomIngredient = null;

    #[ORM\Column(type:'boolean')]
    private ?bool $statutIngredient = false;

    /**
     * @var Collection<int, Recette>
     */
    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'ingredient')]
    private Collection $recettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomIngredient(): ?string
    {
        return $this->nomIngredient;
    }

    public function setNomIngredient(string $nomIngredient): static
    {
        $this->nomIngredient = trim(strtolower($nomIngredient));

        return $this;
    }

    public function isStatutIngredient(): ?bool
    {
        return $this->statutIngredient;
    }

    public function setStatutIngredient(bool $statutIngredient): static
    {
        $this->statutIngredient = $statutIngredient;

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
            $recette->setIngredient($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getIngredient() === $this) {
                $recette->setIngredient(null);
            }
        }

        return $this;
    }

   
}
