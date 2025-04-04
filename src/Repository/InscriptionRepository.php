<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inscription>
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
            parent::__construct($registry, Inscription::class);
    }

    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.laFormation', 'f') // Joindre la table Formation
            ->andWhere('i.lemploye = :userId') // Filtrer par employé
            ->setParameter('userId', $userId)
            ->orderBy('f.dateDebut', 'ASC') // Optionnel : Trier par date de formation
            ->getQuery()
            ->getResult();
    }


    public function findFormationsExcludingUser($userId): array
    {
        // Créer un QueryBuilder basé sur l'entité Inscription
        $qb = $this->createQueryBuilder('i');
    
        return $qb->select('f')
            ->from(Formation::class, 'f') // On part de l'entité Formation
            ->leftJoin('i.laFormation', 'fi') // Joindre les inscriptions associées aux formations
            ->andWhere('fi.id IS NULL OR i.lemploye != :userId') // Pas d'inscription de l'utilisateur
            ->setParameter('userId', $userId) // ID de l'utilisateur à exclure
            ->getQuery()
            ->getResult();
    }

    public function rechInscriptionsEmployeNomPrenom($nom, $prenom) : array
    {
        return $this->createQueryBuilder('i')
        ->join('i.lemploye', 'e')
        ->andWhere('e.nom = :val1')
        ->andWhere('e.prenom = :val2')
        ->setParameter('val1', $nom)
        ->setParameter('val2', $prenom)
        ->getQuery()
        ->getResult()
        ;

    }

    public function findFutureFormationsByEmploye(int $employeId): array
    {
        // Récupérer toutes les inscriptions de l'employé
        $inscriptions = $this->createQueryBuilder('i')
            ->where('i.lemploye = :employeId')
            ->setParameter('employeId', $employeId)
            ->getQuery()
            ->getResult();

        // Filtrer les formations futures
        $futureFormations = [];
        foreach ($inscriptions as $inscription) {
            $formation = $inscription->getLaFormation();
            
            // Si la formation existe et que la date de début est dans le futur, on l'ajoute
            if ($formation && $formation->getDateDebut() > new \DateTime()) {
                $futureFormations[] = $formation;
            }
        }

        return $futureFormations;
    }

    public function save(Inscription $inscription): void
    {
        $entityManager = $this->getEntityManager();

        // Si l'inscription existe déjà (id non null), Doctrine la mettra à jour automatiquement.
        // Si l'id est nul, Doctrine va créer une nouvelle entrée.
        $entityManager->persist($inscription);
        $entityManager->flush(); // Enregistre les changements dans la base de données
    }

    public function remove(Inscription $inscription): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($inscription);
        $entityManager->flush(); // Enregistre les changements dans la base de données
    }
    




    //    /**
    //     * @return Inscription[] Returns an array of Inscription objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Inscription
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
