<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Produit;
use App\Form\FormationFormType;
use App\Form\ProduitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/ajoutProduit', name:'ajout_produit')]
    public function ajoutProduit(Request $request, ManagerRegistry $doctrine, $produit=null){
        if ($produit == null){
            $produit = new Produit();
        }
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($produit);
            $em->flush();
        }

        return $this->render('produit/ajout.html.twig', array('form'=>$form->createView()));

        
    }
}
