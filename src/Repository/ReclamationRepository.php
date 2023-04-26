<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *  
*/
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
 
    
    public function countAll(): int
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('COUNT(r.id)');
        $query = $qb->getQuery();
        return (int) $query->getSingleScalarResult();
    }

   public function findNewest(): int
   {
       $qb = $this->createQueryBuilder('r');
       $qb->select('COUNT(r.id)')
           ->where('r.datecreation >= :date')
           ->setParameter('date', new \DateTime('-7 days'));
       $query = $qb->getQuery();
       return (int) $query->getSingleScalarResult();
   }
   public function countByDay(): array
   {
       $qb = $this->createQueryBuilder('r');
       $qb->select("r.datecreation AS day, COUNT(r.id) AS count")
          ->groupBy('day')
          ->orderBy('day');
       $query = $qb->getQuery();
       return $query->getResult();
   }
   
   public function findOldests(): array
   {
       $qb = $this->createQueryBuilder('r');
       $qb->select('COUNT(r.id)')
           ->where('r.datecreation < :date')
           ->setParameter('date', new \DateTime('-7 days'));
           $query = $qb->getQuery();
           return $query->getResult();
   }



   public function findOldest(): int
   {
       $qb = $this->createQueryBuilder('r');
       $qb->select('COUNT(r.id)')
           ->where('r.datecreation < :date')
           ->setParameter('date', new \DateTime('-7 days'));
       $query = $qb->getQuery();
       return (int) $query->getSingleScalarResult();
   }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBy(array $criteria, ?array $orderBy = null)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.' . key($criteria) . ' = :' . key($criteria))
            ->setParameter(key($criteria), $criteria[key($criteria)])
            ->orderBy($orderBy)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
  /*
    public function countByDay(): array
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('DATE(r.datecreation) AS day, COUNT(r.id) AS count')
           ->groupBy('day')
           ->orderBy('day');
        $query = $qb->getQuery();
        return $query->getResult();
    }
     
*/
}
