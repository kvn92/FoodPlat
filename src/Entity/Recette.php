<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[NotBlank]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Votre titre doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Votre titre ne peut pas contenir plus de {{ limit }} caractères.'
    )]
    private ?string $titreRecette = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?Difficulte $difficulte = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
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
    #[NotBlank()]
    #[Positive()]
    private ?int $duree = null;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'recette')]
    private Collection $commentaires;
    
    public function __construct()
    {
        $this->datePublication = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->nbLike = 0;
        $this->statutRecette = false;
        $this->commentaires = new ArrayCollection();
        
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
}
