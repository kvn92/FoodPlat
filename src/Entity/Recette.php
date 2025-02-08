<?php

namespace App\Entity;

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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer',name:'id_recette')]
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

    #[ORM\ManyToOne(targetEntity:Categorie::class,inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'categorie_id', referencedColumnName: 'id_categorie')]
    private ?Categorie $categorie = null;


    #[ORM\ManyToOne(targetEntity:Difficulte::class,inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'difficulte_id', referencedColumnName: 'id_difficulte')]
    private ?Difficulte $difficulte = null;

    #[ORM\ManyToOne(targetEntity:TypeRepas::class,inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'type_repas_id', referencedColumnName: 'id_type_repas')]
    private ?TypeRepas $typeRepas = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    // Définit la valeur par défaut de nbLike à 0
    #[ORM\Column(type:'integer')]
    #[PositiveOrZero()]
    private ?int $nbLike = null;

    #[ORM\Column(type:'string', length: 255, nullable: true)]
    private ?string $photoRecette = null;

    #[ORM\Column(type:'boolean',nullable: true)]
    private ?bool $statutRecette = null;

    #[ORM\Column(type:'integer')]
    #[Positive()]
    private ?int $duree = null;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'recette')]
    #[ORM\JoinColumn(name: 'commentaire_id', referencedColumnName: 'id_commentaire')]
    private Collection $commentaires;

    /**
     * @var Collection<int, LikeRecette>
     */
    #[ORM\OneToMany(targetEntity: LikeRecette::class, mappedBy: 'recette')]
    #[ORM\JoinColumn(name: 'like_recette_id', referencedColumnName: 'id_like_recette')]
    private Collection $likeRecettes;

    /**
     * @var Collection<int, Etape>
     */
    #[ORM\OneToMany(targetEntity: Etape::class, mappedBy: 'recette')]
    #[ORM\JoinColumn(name: 'etape_id', referencedColumnName: 'id_etape')]
    private Collection $etapes;

    
    #[ORM\ManyToOne(targetEntity: Pays::class, inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'pays_id', referencedColumnName: 'id_pays')]
    private ?Pays $pays = null;


    #[ORM\ManyToOne(targetEntity:Viande::class,inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'viande_id', referencedColumnName: 'id_viande')]
    private ?Viande $viande = null;

    #[ORM\ManyToOne(targetEntity:Ingredient::class,inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'ingredient_id', referencedColumnName: 'id_ingredient')]
    private ?Ingredient $ingredient = null;


    #[ORM\ManyToOne(targetEntity:Utilisateur::class,inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id_utilisateur')]
    private ?Utilisateur $utilisateur = null;

    /**
     * @var Collection<int, Favori>
     */
    #[ORM\OneToMany(targetEntity: Favori::class, mappedBy: 'recette')]
    private Collection $favoris;


  


    
    public function __construct()
    {
        $this->datePublication = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->nbLike = 0;
        $this->statutRecette = false;
        $this->commentaires = new ArrayCollection();
        $this->likeRecettes = new ArrayCollection();
        $this->etapes = new ArrayCollection();
        
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreRecette(): ?string
    {
        return $this->titreRecette;
    }

    public function setTitreRecette(string $titreRecette): static
    {
        $this->titreRecette = strtolower(trim($titreRecette));
        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getDifficulte(): ?Difficulte
    {
        return $this->difficulte;
    }

    public function setDifficulte(?Difficulte $difficulte): static
    {
        $this->difficulte = $difficulte;
        return $this;
    }

    public function getTypeRepas(): ?TypeRepas
    {
        return $this->typeRepas;
    }

    public function setTypeRepas(?TypeRepas $typeRepas): static
    {
        $this->typeRepas = $typeRepas;
        return $this;
    }


   

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;
        return $this;
    }

    public function getNbLike(): ?int
    {
        return $this->nbLike;
    }

    public function setNbLike(?int $nbLike): static
    {
        $this->nbLike = $nbLike;
        return $this;
    }

    public function getPhotoRecette(): ?string
    {
        return $this->photoRecette;
    }

    public function setPhotoRecette(?string $photoRecette): static
    {
        $this->photoRecette = strtolower(trim($photoRecette));
        return $this;
    }

    public function isStatutRecette(): ?bool
    {
        return $this->statutRecette;
    }

    public function setStatutRecette(?bool $statutRecette): static
    {
        $this->statutRecette = $statutRecette;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setRecette($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getRecette() === $this) {
                $commentaire->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LikeRecette>
     */
    public function getLikeRecettes(): Collection
    {
        return $this->likeRecettes;
    }

    public function addLikeRecette(LikeRecette $likeRecette): static
    {
        if (!$this->likeRecettes->contains($likeRecette)) {
            $this->likeRecettes->add($likeRecette);
            $likeRecette->setRecette($this);
        }

        return $this;
    }

    public function removeLikeRecette(LikeRecette $likeRecette): static
    {
        if ($this->likeRecettes->removeElement($likeRecette)) {
            // set the owning side to null (unless already changed)
            if ($likeRecette->getRecette() === $this) {
                $likeRecette->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Etape>
     */
    public function getEtapes(): Collection
    {
        return $this->etapes;
    }

    public function addEtape(Etape $etape): static
    {
        if (!$this->etapes->contains($etape)) {
            $this->etapes->add($etape);
            $etape->setRecette($this);
        }

        return $this;
    }

    public function removeEtape(Etape $etape): static
    {
        if ($this->etapes->removeElement($etape)) {
            // set the owning side to null (unless already changed)
            if ($etape->getRecette() === $this) {
                $etape->setRecette(null);
            }
        }

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getViande(): ?Viande
    {
        return $this->viande;
    }

    public function setViande(?Viande $viande): static
    {
        $this->viande = $viande;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * @return Collection<int, Favori>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favori $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setRecette($this);
        }

        return $this;
    }

    public function removeFavori(Favori $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getRecette() === $this) {
                $favori->setRecette(null);
            }
        }

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
