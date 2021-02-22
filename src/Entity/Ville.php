<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
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
     * @ORM\Column (type="integer", length=5)
     * @Groups({"liste_sorties"})
     */
    private  $codePostal;



    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Lieu", mappedBy="ville")
     */
    private $lieux;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
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
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * @param mixed $codePostal
     */
    public function setCodePostal($codePostal): void
    {
        $this->codePostal = $codePostal;
    }
    /**
     * @return Collection
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    /**
     * @param Collection $lieux
     */
    public function setLieux(Collection $lieux): void
    {
        $this->lieux = $lieux;
    }

}
