<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Positive;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    // === ATTRIBUTS PRINCIPAUX ===
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_utilisateur')]
    #[Positive()]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[NotBlank(message: 'Obligatoire')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre email doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre email ne peut pas contenir plus de {{ limit }} caractères'
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[NotCompromisedPassword]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre pseudo doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre pseudo ne peut pas contenir plus de {{ limit }} caractères'
    )]
    private ?string $pseudo = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $statutUtilisateur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    // === SUIVI / FOLLOWERS ===
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followers')]
    #[ORM\JoinTable(
        name: 'user_followers',
        joinColumns: [new ORM\JoinColumn(name: 'follower_id', referencedColumnName: 'id_utilisateur')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'following_id', referencedColumnName: 'id_utilisateur')]
    )]
    private Collection $following;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'following')]
    private Collection $followers;

    // === RELATIONS AVEC D'AUTRES ENTITÉS ===
    #[ORM\OneToMany(targetEntity: LikeRecette::class, mappedBy: 'utilisateur')]
    private Collection $likeRecettes;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'utilisateur')]
    private Collection $commentaires;

    #[ORM\OneToMany(targetEntity: Favori::class, mappedBy: 'utilisateur')]
    #[ORM\JoinColumn(name: 'favori_id', referencedColumnName: 'id_favori')]
    private Collection $favoris;

    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'utilisateur')]
    private Collection $recettes;

    // === CONSTRUCTEUR ===
    public function __construct()
    {
        $this->dateInscription = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->following = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->likeRecettes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->recettes = new ArrayCollection();
    }

    // === GETTERS & SETTERS ===
    public function getId(): ?int { return $this->id; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static
    {
        $this->email = strtolower(trim($email));
        return $this;
    }

    public function getUserIdentifier(): string { return (string) $this->email; }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void {}

    public function getPseudo(): ?string { return $this->pseudo; }
    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = strtolower(trim($pseudo));
        return $this;
    }

    public function getPhoto(): ?string { return $this->photo; }
    public function setPhoto(?string $photo): static
    {
        $this->photo = strtolower(trim($photo));
        return $this;
    }

    public function isStatutUtilisateur(): ?bool { return $this->statutUtilisateur; }
    public function setStatutUtilisateur(?bool $statutUtilisateur): static { $this->statutUtilisateur = $statutUtilisateur; return $this; }

    public function getDateInscription(): ?\DateTimeInterface { return $this->dateInscription; }
    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    // === FOLLOWING / FOLLOWERS ===
    public function getFollowing(): Collection { return $this->following; }
    public function addFollowing(self $user): self
    {
        if (!$this->following->contains($user)) {
            $this->following->add($user);
            $user->addFollower($this);
        }
        return $this;
    }

    public function removeFollowing(self $user): self
    {
        if ($this->following->removeElement($user)) {
            $user->removeFollower($this);
        }
        return $this;
    }

    public function getFollowers(): Collection { return $this->followers; }
    public function addFollower(self $user): self
    {
        if (!$this->followers->contains($user)) {
            $this->followers->add($user);
        }
        return $this;
    }

    public function removeFollower(self $user): self
    {
        $this->followers->removeElement($user);
        return $this;
    }

    public function __toString(): string { return $this->pseudo ?? $this->email ?? 'Utilisateur inconnu'; }

    // === COLLECTIONS RELATIONNELLES ===
    public function getLikeRecettes(): Collection { return $this->likeRecettes; }
    public function getCommentaires(): Collection { return $this->commentaires; }
    public function getFavoris(): Collection { return $this->favoris; }
    public function getRecettes(): Collection { return $this->recettes; }
}
