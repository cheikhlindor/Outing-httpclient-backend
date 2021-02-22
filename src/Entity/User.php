<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"liste_sorties"})
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Entrer un nom valide")
     * @ORM\Column (type="string", length=50)
     * @Groups({"liste_sorties"})
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Entrer un prenom valide")
     * @ORM\Column (type="string", length=50)
     * @Groups({"liste_sorties"})
     */
    private $prenom;

    /**
     * @Assert\NotBlank(message="Entrer un username valide")
     * @Assert\Length(min=4, minMessage="Le username doit faire au mois 4 caractères")
     * @ORM\Column (type="string", length=50, unique=true)
     * @Groups({"liste_sorties"})
     */
    private $username;


    /**
     * @Assert\NotBlank(message="Entrer un téléphone valide")
     * @ORM\Column (type="string", length=10)
     * @Groups({"liste_sorties"})
     */
    private $telephone;

    /**
     * @Assert\Email(message="entrer un email valide")
     * @ORM\Column (type="string", length=50, unique=true)
     * @Groups({"liste_sorties"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sorties;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sortie", mappedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sorts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\Column(type="json")
     */
    private $roles=[];

    /**
     * @return mixed
     */
    public function getRoles():array
    {
        $roles = $this->roles;
        $roles[]='ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param mixed $roles
     */
    public function setRoles( $roles)
    {
        $this->roles = $roles;

    }
    public function addSortie(Sortie $sortie)
    {
        if(!$this->sorts->contains($sortie)){
            $this->sorts->add($sortie);
        }
    }

    /**
     * @return Campus
     */
    public function getCampus():? Campus
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     */
    public function setCampus($campus): void
    {
        $this->campus = $campus;
    }

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sorts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAdministrateur()
    {
        return $this->administrateur;
    }

    /**
     * @param mixed $administrateur
     */
    public function setAdministrateur($administrateur): void
    {
        $this->administrateur = $administrateur;
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     */
    public function setActif($actif): void
    {
        $this->actif = $actif;
    }

    /**
     * @return mixed
     */



    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /*public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }*/

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }
}
