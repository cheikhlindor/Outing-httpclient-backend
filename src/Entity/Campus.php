<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"liste_sorties"})
     */
    private $id;
    /**
     * @ORM\Column (type="string", length=50)
     * @Groups({"liste_sorties"})
     */
    private  $nom;


    /**
     * @ORM\OneToMany (targetEntity="App\Entity\Sortie", mappedBy="campus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sorties;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\User", mappedBy="campus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->users = new ArrayCollection();
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

}
