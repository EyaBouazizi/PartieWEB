<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM App:Livre p
                WHERE p.titre LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Livre $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Livre $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findBygetEntityFromDate(\DateTime $date, $max)
    {
        return $this->getEntityManager()
            ->createQueryBuilder('d')
            ->select('d.idlivre')
            ->from('App:Livre', 'd')
            ->orderBy('d.date', 'DESC')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setMaxResults($max)
            ->getQuery();


    }

    /**
     * Returns number of "livre"
     * @return void
     */

    public function findByDate()
    {

        $entyManager = $this->getEntityManager();
        $query = $entyManager
            ->createQuery("select a FROM APP\Entity\Livre a GROUP BY a.date")
            ->setMaxResults(3);

        return $query->getResult();
    }

    /**
     * Returns number of "livre"
     * @return void
     */

    public function findByhisto()
    {

        $entyManager = $this->getEntityManager();
        $query = $entyManager
            ->createQuery("select a FROM APP\Entity\Livre a GROUP BY a.id_categorie order by count(*) DESC ")
            ->setMaxResults(1);

        return $query->getResult();
    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Livre[]
     */
    public function findSearch(SearchData $search): array
    {

        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.idCategorie', 'c');

        if (!empty($search->idCategorie)) {
            $query = $query
                ->andWhere('c.idCategorie IN (:idCategorie)')
                ->setParameter('nomCategorie', "%{$search->nomCategorie}%");
        }

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.titre LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }


        return $query->getQuery()->getResult();
    }
}
