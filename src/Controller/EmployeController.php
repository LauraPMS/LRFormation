<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Repository\FormationRepository;
use App\Repository\InscriptionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    // Affiche le tableau de bord de l'employe dit basique. 
    #[Route('/dashboard', name: 'app_employe_dashboard')]
    public function dashboard(ManagerRegistry $doctrine, InscriptionRepository $inscriptionRepository, SessionInterface $session): Response {

        // Récupérer l'ID de l'utilisateur
        $userId = $session->get('user_id');
        
        // Vérifier si l'utilisateur a le bon statut
        if ($session->get('user_statut') != 1) {
            return $this->redirectToRoute('app_connexion');
        }

        // Récupérer toutes les inscriptions de l'utilisateur
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findBy(['lemploye' => $userId]);

        // Récupérer toutes les formations futures
        $formations = $doctrine->getManager()->getRepository(Formation::class)->createQueryBuilder('f')->where('f.dateDebut > :currentDate')->setParameter('currentDate', new \DateTime())->getQuery()->getResult();

        // Exclure les formations où l'utilisateur est inscrit
        $formationIdsInscrites = array_map(function($inscription) {
            return $inscription->getLaFormation()->getId();
        }, $inscriptions);

        // Filtrer les formations pour exclure celles où l'utilisateur est inscrit
        $formationsDisponibles = array_filter($formations, function($formation) use ($formationIdsInscrites) {
            return !in_array($formation->getId(), $formationIdsInscrites);
        });

        // Récupérer l'employé
        $employe = $doctrine->getManager()->getRepository(Employe::class)->find($userId);

        // Afficher dans la vue
        return $this->render('employe/dashboard.html.twig', [
            'formations' => $formationsDisponibles,
            'user' => $employe,
        ]);

    }


  /*  // Inscription de l'employe a une formation 
    #[Route('/inscrireFormation/{idFormation}', name:'app_inscrireFormation')]
    public function inscrireFormation( ManagerRegistry $doctrine, SessionInterface $session, $idFormation){


        $userId = $session->get('user_id');
        $userStatut = $session->get('user_statut');
        $entityManager = $doctrine->getManager();

        // L'inscription est possible seulement si c'est a la demande d'un employe (le cas dune inscription responsbla n'est pas demandé)
        if ($userStatut != 1){
            return $this->redirectToRoute('app_connexion');
        }
    
        // Recuperation de l'employe et de la formation
        $user = $doctrine->getManager()->getRepository(Employe::class)->find($userId);
        $formation = $doctrine->getManager()->getRepository(Formation::class)->find($idFormation);

        // Si la formation avec l'idFormation existe on procede a l'inscription
        if ($formation) {

            $inscription = new Inscription();
            $inscription->setLemploye($user);
            $inscription->setLaFormation($formation);
            $inscription->setStatut('0');

            $entityManager->persist($inscription);
            $entityManager->flush();
        
        }
        return $this->redirectToRoute('app_employe_dashboard');

    } */


    #[Route('/AllInscriptions', name:'app_afficherAllInscriptions')]
    public function afficherFormationNonApprouve(SessionInterface $session, InscriptionRepository $inscriptionRepository){
        
        // Vérification de l'utilisateur
        $userId = $session->get('user_id');
        $userStatut = $session->get('user_statut');

        if ($userStatut != 1) {
            return $this->redirectToRoute('app_connexion');
        }

        $inscriptions = $inscriptionRepository->findByUserId($userId);

        return $this->render('employe/afficherInscription.html.twig', [
            'inscriptions' => $inscriptions,
        ]);
    }

    #[Route('/Formations', name:'app_empFormations')]
    public function afficherFormationsDisponible(SessionInterface $session, FormationRepository $formation_repository, InscriptionRepository $inscription_repository)
    {
        // Vérification de l'utilisateur
        $userId = $session->get('user_id');
        $userStatut = $session->get('user_statut');
    
        if ($userStatut != 1) {
            return $this->redirectToRoute('app_connexion');
        }
    
        // Récupérer toutes les formations futures
        $currentDate = new \DateTime();
        $formationsFutures = $formation_repository->createQueryBuilder('f')
            ->where('f.dateDebut > :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getResult();
    
        // Vérifier si l'utilisateur est inscrit à ces formations
        $inscriptions = $inscription_repository->findBy(['lemploye' => $userId]);
    
        // Passer les formations et les inscriptions à la vue
        return $this->render('formation/liste.html.twig', [
            'formations' => $formationsFutures,
            'inscriptions' => $inscriptions,
        ]);
    }

    #[Route('/formation/{idFormation}/inscrire', name: 'app_inscrireFormation')]
    public function inscrireFormation($idFormation, SessionInterface $session, InscriptionRepository $inscription_repository, FormationRepository $formation_repository, ManagerRegistry $doctrine)
    {
        $userId = $session->get('user_id');
        $user = $doctrine->getManager()->getRepository(Employe::class)->find($userId);
        
        // Récupérer la formation
        $formation = $formation_repository->find($idFormation);
        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée');
        }

        // Créer une inscription
        $inscription = new Inscription();
        $inscription->setLemploye($user);
        $inscription->setLaFormation($formation);
        $inscription->setStatut(0);

        // Sauvegarder l'inscription
        $inscription_repository->save($inscription);

        return $this->redirectToRoute('app_empFormations');
    }

    #[Route('/formation/{idFormation}/desinscrire', name: 'app_desinscrireFormation')]
    public function desinscrireFormation($idFormation, SessionInterface $session, InscriptionRepository $inscription_repository, ManagerRegistry $doctrine)
    {
        $userId = $session->get('user_id');
        $user = $doctrine->getManager()->getRepository(Employe::class)->find($userId);
        
        // Récupérer l'inscription
        $inscription = $inscription_repository->findOneBy([
            'lemploye' => $user,
            'laFormation' => $idFormation,
        ]);
        
        if ($inscription) {
            // Supprimer l'inscription
            $inscription_repository->remove($inscription);
        }

        return $this->redirectToRoute('app_empFormations');
    }

    


}
