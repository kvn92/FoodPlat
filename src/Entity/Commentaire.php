<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\IsFalse;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer')]
    private ?int $id = null;

    #[ORM\Column(Types::TEXT,length:300)]
    #[NotBlank]
    #[Assert\Length(
        min:3,
        max:300,
        minMessage: 'Votre titre doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Votre titre ne peut pas contenir plus de {{ limit }} caractères.'
    )]
    private ?string $commentaire = null;

    #[ORM\Column( type:'boolean',nullable: true)]
    #[IsFalse()]
    private ?bool $statutCommentaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCommentaire = null;

   
    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Recette $recette = null;

    public function __construct()
    {
        $this->statutCommentaire = false;
        $this->dateCommentaire = new DateTime('now', new \DateTimeZone('Europe/Paris'));
    }

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
        $this->commentaire = $commentaire;

        return $this;
    }

    public function isStatutCommentaire(): ?bool
    {
        return $this->statutCommentaire;
    }

    public function setStatutCommentaire(?bool $statutCommentaire): static
    {
        $this->statutCommentaire = $statutCommentaire;

        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(?\DateTimeInterface $dateCommentaire): static
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

}
