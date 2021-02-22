<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SortieRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 * @ApiResource()
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"liste_sorties"})
     */
    private $id;

    /**
     * @ORM\Column (type="string", length=40)
     * @Groups({"liste_sorties"})
     */
    private  $nom;

    /**
     * @ORM\Column (type="datetime")
     * @Groups({"liste_sorties"})
     */
    private  $dateHeureDebut;

    /**
     * @ORM\Column (type="integer")
     * @Groups({"liste_sorties"})
     */
    private  $duree;

    /**
     * @ORM\Column (type="datetime")
     * @Groups({"liste_sorties"})
     */
    private  $dateLimiteInscription;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Groups({"liste_sorties"})
     */
    private $isactive=true;
    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Groups({"liste_sorties"})
     */
    private $motif;
    /**
     * @ORM\Column (type="integer")
     * @Groups({"liste_sorties"})
     */
    private  $nbInscriptionMax;

    /**
     * @ORM\Column (type="string", length=255)
     * @Groups({"liste_sorties"})
     */
    private  $infoSortie;

    /**
     * @ORM\Column (type="string", nullable=true)
     * @Groups({"liste_sorties"})
     */
    private  $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"liste_sorties"})
     */
    private $etatsortie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"liste_sorties"})
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"liste_sorties"})
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"liste_sorties"})
     */
    private $user;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="sorts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"liste_sorties"})
     */
    private $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Lieu
     */
    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    /**
     * @param Lieu $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return Etat
     */
    public function getEtatsortie(): ?Etat
    {
        return $this->etatsortie;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     */
    public function setUsers(ArrayCollection $users): void
    {
        $this->users = $users;
    }
    public function addUsers(User $user)
    {
        $user->addSortie($this);
        /*$user->setSortie($this); for many-to-one*/
        $this->users->add($user);
    }
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * @param Etat $etatsortie
     */
    public function setEtatsortie(Etat $etatsortie): void
    {
        $this->etatsortie = $etatsortie;
    }
    /**
     * @return Campus
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     */
    public function setCampus(Campus $campus): void
    {
        $this->campus = $campus;
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
    public function getDateHeureDebut()
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param mixed $dateHeureDebut
     */
    public function setDateHeureDebut($dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return mixed
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param mixed $duree
     */
    public function setDuree($duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return mixed
     */
    public function getDateLimiteInscription()
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param mixed $dateLimiteInscription
     */
    public function setDateLimiteInscription($dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    /**
     * @return mixed
     */
    public function getNbInscriptionMax()
    {
        return $this->nbInscriptionMax;
    }

    /**
     * @param mixed $nbInscriptionMax
     */
    public function setNbInscriptionMax($nbInscriptionMax): void
    {
        $this->nbInscriptionMax = $nbInscriptionMax;
    }

    /**
     * @return mixed
     */
    public function getInfoSortie()
    {
        return $this->infoSortie;
    }

    /**
     * @param mixed $infoSortie
     */
    public function setInfoSortie($infoSortie): void
    {
        $this->infoSortie = $infoSortie;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return bool
     */
    public function isIsactive(): bool
    {
        return $this->isactive;
    }

    /**
     * @param bool $isactive
     */
    public function setIsactive(bool $isactive): void
    {
        $this->isactive = $isactive;
    }

    /**
     * @return mixed
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * @param mixed $motif
     */
    public function setMotif($motif): void
    {
        $this->motif = $motif;
    }

}
