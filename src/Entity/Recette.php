<?php

namespace App\Entity;

use App\Enum\CategorieEnum;
use App\Enum\DifficulterEnum;
use App\Enum\IngredientEnum;
use App\Enum\PaysEnum;
use App\Enum\RepasEnum;
use App\Enum\ViandeEnum;
use App\Repository\RecetteRepository;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

#[UniqueEntity('titreRecette', message: '{{ value }} est déjà enregistré.')]
#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    // === ATTRIBUTS PRINCIPAUX ===
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_recette')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[NotBlank()]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Votre titre doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Votre titre ne peut pas contenir plus de {{ limit }} caractères.'
    )]
    private ?string $titreRecette = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\Column(type: 'integer')]
    #[PositiveOrZero()]
    private ?int $nbLike = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photoRecette = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $statutRecette = null;

    #[ORM\Column(type: 'integer')]
    #[Positive()]
    private ?int $duree = null;

    // === ENUMÉRATIONS ===
    #[ORM\Column(type: 'integer', nullable: true, name: 'pays_id')]
    private ?int $pays = null;

    #[ORM\Column(type: 'integer', nullable: true, name: 'difficulte_id')]
    private ?int $difficulte = null;
   
    #[ORM\Column(type: 'integer', nullable: true, name: 'categorie_id')]
    private ?int $categorie = null;
    
    #[ORM\Column(type: 'integer', nullable: true, name: 'viande_id')]
    private ?int $viande = null;
   
    #[ORM\Column(type: 'integer', nullable: true, name: 'ingredient_id')]
    private ?int $ingredient = null;

    #[ORM\Column(type: 'integer', nullable: true, name: 'repas_id')]
    private ?int $repas = null;

    // === RELATIONS ===
    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id_utilisateur')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'recette')]
    private Collection $commentaires;

    #[ORM\OneToMany(targetEntity: LikeRecette::class, mappedBy: 'recette')]
    private Collection $likeRecettes;

    #[ORM\OneToMany(targetEntity: Etape::class, mappedBy: 'recette')]
    private Collection $etapes;

    #[ORM\OneToMany(targetEntity: Favori::class, mappedBy: 'recette')]
    private Collection $favoris;

    // === CONSTRUCTEUR ===
    public function __construct()
    {
        $this->datePublication = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->nbLike = 0;
        $this->statutRecette = false;
        $this->commentaires = new ArrayCollection();
        $this->likeRecettes = new ArrayCollection();
        $this->etapes = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    // === GETTERS & SETTERS ===
    public function getId(): ?int { return $this->id; }

    public function getTitreRecette(): ?string { return $this->titreRecette; }
    public function setTitreRecette(string $titreRecette): static
    {
        $this->titreRecette = strtolower(trim($titreRecette));
        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface { return $this->datePublication; }
    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;
        return $this;
    }

    public function getNbLike(): ?int { return $this->nbLike; }
    public function setNbLike(?int $nbLike): static { $this->nbLike = $nbLike; return $this; }

    public function getPhotoRecette(): ?string { return $this->photoRecette; }
    public function setPhotoRecette(?string $photoRecette): static
    {
        $this->photoRecette = strtolower(trim($photoRecette));
        return $this;
    }

    public function isStatutRecette(): ?bool { return $this->statutRecette; }
    public function setStatutRecette(?bool $statutRecette): static { $this->statutRecette = $statutRecette; return $this; }

    public function getDuree(): ?int { return $this->duree; }
    public function setDuree(?int $duree): static { $this->duree = $duree; return $this; }

    
    public function getPays(): ?PaysEnum
{
    return $this->pays !== null ? PaysEnum::fromInt($this->pays) : null;
}

public function setPays(?PaysEnum $pays): self
{
    $this->pays = $pays?->value; // Stocke uniquement l'int en base de données
    return $this;
}


public function getDifficulte(): ?DifficulterEnum
{
    return $this->difficulte !== null ? DifficulterEnum::fromInt($this->difficulte) : null;
}

public function setDifficulte(?DifficulterEnum $difficulte): self
{
    $this->difficulte = $difficulte?->value; // Stocke uniquement l'int en base de données
    return $this;
}


public function getCategorie(): ?CategorieEnum
{
    return $this->categorie !== null ? CategorieEnum::fromInt($this->categorie) : null;
}

public function setCategorie(?CategorieEnum $categorie): self
{
    $this->categorie = $categorie?->value; // Stocke uniquement l'int en base de données
    return $this;
}
    


public function getIngredient(): ?IngredientEnum
{
    return $this->ingredient !== null ? IngredientEnum::fromInt($this->ingredient) : null;
}

public function setIngredient(?IngredientEnum $ingredient): self
{
    $this->ingredient = $ingredient?->value; // Stocke uniquement l'int en base de données
    return $this;
}
    

public function getRepas(): ?RepasEnum
{
    return $this->repas !== null ? RepasEnum::fromInt($this->repas) : null;
}

public function setRepas(?RepasEnum $repas): self
{
    $this->repas = $repas?->value; // Stocke uniquement l'int en base de données
    return $this;
}
    
public function getViande(): ?ViandeEnum
{
    return $this->viande !== null ? ViandeEnum::fromInt($this->viande) : null;
}

public function setViande(?ViandeEnum $viande): self
{
    $this->viande = $viande?->value; // Stocke uniquement l'int en base de données
    return $this;
}
   



    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $utilisateur): static { $this->utilisateur = $utilisateur; return $this; }

    public function getCommentaires(): Collection { return $this->commentaires; }
    public function getLikeRecettes(): Collection { return $this->likeRecettes; }
    public function getEtapes(): Collection { return $this->etapes; }
    public function getFavoris(): Collection { return $this->favoris; }
}
