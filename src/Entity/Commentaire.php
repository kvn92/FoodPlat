<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    // === ATTRIBUTS PRINCIPAUX ===
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_commentaire')]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, length: 300)]
    #[Assert\NotBlank(message: 'Le commentaire ne peut pas être vide.')]
    #[Assert\Length(
        min: 3,
        max: 300,
        minMessage: 'Votre commentaire doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Votre commentaire ne peut pas contenir plus de {{ limit }} caractères.'
    )]
    private ?string $commentaire = null;

    #[ORM\Column(type: 'boolean')]
    private bool $statutCommentaire = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommentaire = null;

    // === RELATIONS ===
    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(name: "recette_id", referencedColumnName: "id_recette", nullable: false)]
    private ?Recette $recette = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id_utilisateur", nullable: false)]
    private ?Utilisateur $utilisateur = null;

    // === CONSTRUCTEUR ===
    public function __construct()
    {
        $this->statutCommentaire = false;
        $this->dateCommentaire = new DateTime('now', new DateTimeZone('Europe/Paris'));
    }

    // === GETTERS & SETTERS ===
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = trim($commentaire);
        return $this;
    }

    public function isStatutCommentaire(): bool
    {
        return $this->statutCommentaire;
    }

    public function setStatutCommentaire(bool $statutCommentaire): static
    {
        $this->statutCommentaire = $statutCommentaire;
        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $dateCommentaire): static
    {
        $this->dateCommentaire = $dateCommentaire;
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
