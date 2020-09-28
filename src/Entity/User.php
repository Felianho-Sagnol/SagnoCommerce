<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="email", message="Cette adresse email existe déja")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veillez remplir ce champ")
     * @Assert\Length(min=2,minMessage="Vous devez entrer au moins 2 caractère pour votre nom !")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veillez remplir ce champ")
     * @Assert\Length(min=2,minMessage="Vous devez entrer au moins 2 caractère pour votre prenom !")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255,unique = true)
     * @Assert\NotBlank(message="Veillez remplir ce champ")
     * @Assert\Email(message="veillez donner une adresse email valide ex:monemail@gmail.com")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veillez remplir ce champ")
     * @Assert\Length(min=6,minMessage="Vous devez entrer au moins 6 caractère pour votre mot de passe !")
     */
    private $password;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    private $signAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veillez remplir ce champ")
    * @Assert\Length(min=9,minMessage="Veillez entrer un numero valide !")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $userRoles;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }


    /**
     * 
     *@ORM\PrePersist
     *@ORM\PreUpdate
     * 
     */
    public function prePersist(){
        if(empty($this->signAt)){
            $this->signAt = new \DateTime();
        }
    }
    /**
     * Undocumented function
     *@ORM\PrePersist
     *@ORM\PreUpdate
     * @return void
     */
    public function initializeSlug(){
        $slugify = new Slugify();
        if(empty($this->slug)){
            $this->slug = $slugify->slugify($this->firstName.' '.$this->name);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }


    public function getSignAt(): ?\DateTimeInterface
    {
        return $this->signAt;
    }

    public function setSignAt(\DateTimeInterface $signAt): self
    {
        $this->signAt = $signAt;

        return $this;
    }
    public function eraseCredentials(){}

    public function getRoles(): array
    {
        /* chaque role est un array collection pour avoir en tableau simple php on 
         utilise la function map() qui permet de faire la transformation */
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return $roles;
    }

    public function setRoles(array $role):self
    {
        $this->roles[] = $role;

        return $this;
    }

    public function getSalt(){}

    public function getUsername(){
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }
}
