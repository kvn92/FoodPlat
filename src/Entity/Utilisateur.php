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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer', name:'id_utilisateur')]
    #[Positive()]
    private ?int $idUtilisateur = null;

    #[ORM\Column(type:'string',length: 255,unique:true)]
    #[NotBlank(message:'Obligatoire')]
    #[Assert\Length( min: 2,
    max: 50,
    minMessage: 'Votre mot doit comporter au moins {{ limit }} caractères',
    maxMessage: 'Votre mot ne peut pas contenir plus de {{ limit }} caractères')]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type:'string')]
    #[NotCompromisedPassword]
    private ?string $password = null;

    #[ORM\Column(type:'string',length: 50,unique:true)]
    #[Assert\Length( min: 2,
    max: 50,
    minMessage: 'Votre mot doit comporter au moins {{ limit }} caractères',
    maxMessage: 'Votre mot ne peut pas contenir plus de {{ limit }} caractères')]
    private ?string $pseudo = null;

    #[ORM\Column(type:'string',length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type:'boolean',nullable: true)]
    private ?bool $statutUtilisateur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followers')]
    #[ORM\JoinTable(
        name: 'user_followers',
        joinColumns: [new ORM\JoinColumn(name: 'follower_id', referencedColumnName: 'id_utilisateur')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'following_id', referencedColumnName: 'id_utilisateur')]
    )]
    private Collection $following;


    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'following')]
    private Collection $followers;


    /**
     * @var Collection<int, LikeRecette>
     */
    #[ORM\OneToMany(targetEntity: LikeRecette::class, mappedBy: 'utilisateur')]
    private Collection $likeRecettes;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'utilisateur')]
    private Collection $commentaires;

    /**
     * @var Collection<int, Favori>
     */
    #[ORM\OneToMany(targetEntity: Favori::class, mappedBy: 'utilisateur')]
    #[ORM\JoinColumn(name: "favori_id", referencedColumnName: "id_favori")]
    private Collection $favoris;

    /**
     * @var Collection<int, Recette>
     */
    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'utilisateur')]
    private Collection $recettes;
    

    public function __construct()
    {
        $this->dateInscription = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->likeRecettes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->recettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->idUtilisateur;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = strtolower(trim($email));

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = strtolower(trim($pseudo));

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = strtolower(trim($photo));

        return $this;
    }

    public function isStatutUtilisateur(): ?bool
    {
        return $this->statutUtilisateur;
    }

    public function setStatutUtilisateur(?bool $statutUtilisateur): static
    {
        $this->statutUtilisateur = $statutUtilisateur;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

     /**
     * @return Collection<int, Utilisateur>
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

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

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

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

    public function __toString(): string
{
    return $this->pseudo ?? $this->email ?? 'Utilisateur inconnu';
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
            $likeRecette->setUtilisateur($this);
        }

        return $this;
    }

    public function removeLikeRecette(LikeRecette $likeRecette): static
    {
        if ($this->likeRecettes->removeElement($likeRecette)) {
            // set the owning side to null (unless already changed)
            if ($likeRecette->getUtilisateur() === $this) {
                $likeRecette->setUtilisateur(null);
            }
        }

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
            $commentaire->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUtilisateur() === $this) {
                $commentaire->setUtilisateur(null);
            }
        }

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
            $favori->setUtilisateur($this);
        }

        return $this;
    }

    public function removeFavori(Favori $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getUtilisateur() === $this) {
                $favori->setUtilisateur(null);
            }
        }

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
            $recette->setUtilisateur($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getUtilisateur() === $this) {
                $recette->setUtilisateur(null);
            }
        }

        return $this;
    }
}


