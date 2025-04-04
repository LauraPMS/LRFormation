<?php

namespace App\Controller;

use App\Repository\EmployeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{

    // Fonction pour se connecter en tant qu'employe
    #[Route('/', name: 'app_connexion')]
    public function connexion(Request $request, EmployeRepository $employeRepository, SessionInterface $session, ManagerRegistry $doctrine): Response
    {

        $login = $request->request->get('login');
        $mdp = $request->request->get('mdp');
        $mdpHashed = md5($mdp . '15');


        // recupérer l'employe a partir de son login et mot de pass
        $employe = $employeRepository->findOneBy([
            'login' => $login,
            'mdp' => $mdpHashed
        ]);
        if (!$employe) {
            return $this->render('connexion/index.html.twig', [
                'error' => 'Identifiants incorrects',
            ]);
        }

        // Remplir la session

        $session->set('user_id', $employe->getId());
        $session->set('user_statut', $employe->getStatut());
        

        // redirection en fonction du statut récupérer
        if ($employe->getStatut() === 1) {
            return $this->redirectToRoute('app_employe_dashboard');
            
        } elseif ($employe->getStatut() === 2) {
            return $this->redirectToRoute('app_responsable_dashboard');
        }

        return $this->render('connexion/index.html.twig', [
            'error' => 'Statut utilisateur inconnu',
        ]);
    }

    
    // Fonction permettant de se déconnecter
    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_connexion');
    }

}
