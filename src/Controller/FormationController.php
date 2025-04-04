<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }

    #[Route('/ajoutFormation', name: 'form_ajoutFormation')]
    public function ajoutFormation(Request $request, ManagerRegistry $doctrine, $formation = null)
    {
        if ($formation == null) {
            $formation = new Formation();
        }
        $form = $this->createForm(FormationFormType::class, $formation);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($formation);
            $em->flush();
        }



        return $this->render('formation/ajout.html.twig', array('form'=>$form->createView()));
        
    }

    #[Route('/allFormations', name: 'app_afficher')]
    public function afficherAllFormations(ManagerRegistry $doctrine, SessionInterface $session) : Response
    {
        $formations = $doctrine->getManager()->getRepository(Formation::class)->findAll();
        if(!$formations)    {
            $message = "Pas de formations";
        }
        else {
            $message = null;
        }

        if($session->get('user_statut') == 1){
            return $this->render('formation/listeformationsInscription.html.twig', array('formations'=>$formations, 'message'=>$message));
        }
        else if($session->get('user_statut') == 2){
            return $this->render('formation/listeformations.html.twig', array('formations'=>$formations, 'message'=>$message));
        }
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);

    }

    #[Route('/affFormationByDate/{month}/{year}', name:'app_AffFormationByDate')]
    public function AfficherFormationByDate(SessionInterface $session, ManagerRegistry $doctrine, $month, $year): Response
    {
        // Récupérer la première et la dernière date du mois
        $startDate = new \DateTime("$year-$month-01");
        $endDate = clone $startDate;
        $endDate->modify('last day of this month');

        // Utiliser QueryBuilder pour récupérer les formations dans la plage de dates
        $formations = $doctrine->getManager()->getRepository(Formation::class)
            ->createQueryBuilder('f')
            ->where('f.dateDebut >= :startDate')
            ->andWhere('f.dateDebut <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('f.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();

        // Retourner la vue avec les formations
        return $this->render('formation/listeformationsByDate.html.twig', [
            'formations' => $formations,
            'month' => $month,
            'year' => $year,
        ]);
    }




    #[Route('/suppFormation/{id}', name: 'app_suppFormation')]
    public function supprimerFormations($id, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $formation = $em->getRepository(Formation::class)->find($id);
    
        if (!$formation) {

            $this->addFlash('error', 'La formation demandée n\'existe pas.');

        } else {

            $em->remove($formation);
            $em->flush();
    
        }

        return $this->redirectToRoute('app_afficher');
    }
    

}
