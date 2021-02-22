<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"liste_sorties"})
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=40)
     * @Groups({"liste_sorties"})
     */
    private  $nom;

    /**
     * @ORM\Column(type="string", length=40)
     * @Groups({"liste_sorties"})
     */
    private  $rue;
    /**
     * @ORM\Column(type="float")
     * @Groups({"liste_sorties"})
     */
    private  $latitude;
    /**
     * @ORM\Column(type="float")
     * @Groups({"liste_sorties"})
     */
    private  $longitude;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="lieux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;



    /**
     * @ORM\OneToMany (targetEntity="App\Entity\Sortie", mappedBy="lieu")
     */
    private $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    /**
     * @return Ville
     */
    public function getVille():?Ville
    {
        return $this->ville;
    }
    /**
     * @param Ville $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

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
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * @param mixed $rue
     */
    public function setRue($rue): void
    {
        $this->rue = $rue;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }
    /**
     * @return Collection
     */
    public function getSorties():? Collection
    {
        return $this->sorties;
    }

    /**
     * @param Collection $sorties
     */
    public function setSorties(Collection $sorties): void
    {
        $this->sorties = $sorties;
    }


}
