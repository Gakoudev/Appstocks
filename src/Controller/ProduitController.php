<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/Produit/liste', name: 'produit_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $p= new Produit();
        $form = $this->createForm(ProduitType::class, $p, array('action'=>$this->generateUrl('produit_add')));
        $data['form'] = $form->createView();
        $data['produits']= $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste.html.twig', $data);
    }

    #[Route('/Produit/add', name: 'produit_add')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        
        $p = new Produit();
        $form = $this->createForm(ProduitType::class,$p);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $p = $form->getData();
            $p->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($p);
            $em->flush();
        }
        
        return $this->redirectToRoute('produit_liste');
    }
}
