<?php


namespace App\DatPersisters;



Use App\Entity\Sortie;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class SortiePersister implements  DataPersisterInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports($data): bool
    {
        return $data instanceof  Sortie;
    }

    public function persist($data)
    {
        //1. Rajouter les  données que l'on veut
        //2. persist des donnes
        //$this->>em->persit($data)
        //$this->>em->flush();

    }

    public function remove($data)
    {
        //$this->>em->remove($data)
        //$this->>em->flush();
    }
}