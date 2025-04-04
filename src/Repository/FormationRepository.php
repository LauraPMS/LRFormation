<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    public function findUpcomingFormations(): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.dateDebut > :today')
            ->setParameter('today', new \DateTime())
            ->orderBy('f.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findFormationsExcludingUser($userId): array
    {
        $qb = $this->createQueryBuilder('i');
        
        return $qb->select('f')
            ->from(Formation::class, 'f') // On part de l'entité Formation
            ->leftJoin('i.laFormation', 'fi') // Joindre les inscriptions associées aux formations
            ->andWhere('fi.id IS NULL OR i.lemploye != :userId') // Pas d'inscription de l'utilisateur
            ->setParameter('userId', $userId) // ID de l'utilisateur à exclure
            ->getQuery()
            ->getResult();
    }

    public function findFutureFormations(): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.dateDebut > :today')
            ->setParameter('today', new \DateTime())
            ->orderBy('f.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }
    


    //    /**
    //     * @return Formation[] Returns an array of Formation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Formation
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
