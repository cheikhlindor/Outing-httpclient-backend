<?php

namespace App\Repository;

use App\data\SearcheData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Sortie::class);
        $this->paginator=$paginator;
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findRecentSortie(SearcheData $search)
    {
       $query = $this->createQueryBuilder('s')
        ->addOrderBy('s.dateHeureDebut', 'DESC')
        ->join('s.campus', 'c')
        ->join('s.user', 'u')
        ->select('c', 's')
        ->select('u', 's')
        ->andWhere('s.isactive=true')
        ->setMaxResults(20);
            if(!empty($search->q))
             {
                 $query = $query
                     ->andWhere('s.nom LIKE :q')
                     ->setParameter('q', $search->q);
            }

            if(!empty($search->dated))
            {
                $query = $query
                ->andWhere('s.dateHeureDebut >= :dated')
                ->setParameter('dated', $search->dated );
            }

            if(!empty($search->datef))
            {
                $query = $query
                ->andWhere('s.dateLimiteInscription <= :datef')
                ->setParameter('datef', $search->datef );
            }
            if(!empty($search->campus))
            {
                $query = $query
                ->andWhere('s.id in (:campus)')
                ->setParameter('campus', $search->campus );
            }
            if(!empty($search->user))
            {
                $query = $query
                ->andWhere('s.id in (:user)')
                ->setParameter('user', $search->user );
            }

              //  return $query ->getQuery()->getResult()
        $query= $query->getQuery();
            return $this ->paginator->paginate(
                $query,
                1,
                15
            );

    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Sortie[]
     */
    public function findSearch():array
    {
        return $this->findAll();
    }
}
