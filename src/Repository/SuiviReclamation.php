<?php

namespace App\Repository;

use App\Entity\SuiviReclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SuiviReclamation>
 *
 * @method SuiviReclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuiviReclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuiviReclamation[]    findAll()
 * @method SuiviReclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuiviReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviReclamation::class);
    }

    public function save(SuiviReclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SuiviReclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}