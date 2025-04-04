<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Formation;
use App\Entity\Inscription;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResponsableController extends AbstractController
{
    #[Route('/responsable/dashboard/', name: 'app_responsable_dashboard')]
    public function dashboard(ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        $statut = $session->get('user_statut');
        $id = $session->get('user_id');

        // Vérification du statut de l'utilisateur
        if ($statut != 2) {
            return $this->redirectToRoute('app_connexion');
        }

        // Récupérer les informations de l'employé connecté
        $employe = $doctrine->getManager()->getRepository(Employe::class)->find($id);

        // Recherche des formations les plus demandées (qui ont le plus d'inscriptions)
        /* $em = $doctrine->getManager();
        $formationsPopulaires = $em->getRepository(Formation::class)
            ->createQueryBuilder('f')
            ->leftJoin('f.inscription', 'i') // Jointure avec la table des inscriptions
            ->groupBy('f.id')
            ->orderBy('COUNT(i.id)', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        // Retourner la vue avec les informations de l'employé et les formations les plus populaires
        return $this->render('responsable/dashboard.html.twig', [
            'user' => $employe,
            'formationsPopulaires' => $formationsPopulaires // Passer les formations populaires à la vue
        ]); */

        return $this->render('responsable/dashboard.html.twig', [
            'user' => $employe,
        ]);
    }

    
    #[Route('/voirInscription', name: 'app_voirInscription')]

    public function voirInscription(ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        
        $userId = $session->get('user_id');
        $userStatut = $session->get('user_statut');
        if($userStatut != 2){
            return $this->redirectToRoute('app_connexion');
        }
        
        $employe = $doctrine->getManager()->getRepository(Employe::class)->find($userId);
        // récupération de toutes les inscriptions qui ont un statut == 0;
        // afficher dasns un tableau avec bordure 
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findBy(['statut' => 0]);

        // Passer les inscriptions à la vue
        return $this->render('responsable/voirInscription.html.twig', [
            'inscriptions' => $inscriptions,
            'user' => $employe,
        ]);
    }   


    #[Route('/validerInscription/{id}', name: 'app_validerInscription')]

    public function validerInscription(int $id, ManagerRegistry $doctrine): Response
    {
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->find($id);

        if (!$inscription) {
            throw $this->createNotFoundException('L\'inscription n\'existe pas.');
        }

        $inscription->setStatut('1');  

        $doctrine->getManager()->flush();

        return $this->redirectToRoute('app_voirInscription');
    }

    #[Route('/refuserInscription/{id}', name: 'app_refuserInscription')]

    public function refuserInscription(int $id, ManagerRegistry $doctrine): Response
    {

        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->find($id);

        if (!$inscription) {
            throw $this->createNotFoundException('L\'inscription n\'existe pas.');
        }

        $inscription->setStatut('3');

        $doctrine->getManager()->flush();

        return $this->redirectToRoute('app_voirInscription');
    }



}
