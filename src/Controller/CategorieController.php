<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{  
    #[Route('/Categorie/liste', name: 'categorie_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $p= new Categorie();
        $form = $this->createForm(CategorieType::class, $p, array('action'=>$this->generateUrl('categorie_add')));
        $data['form'] = $form->createView();
        $data['categories']= $em->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/liste.html.twig', $data);
    }
    
    #[Route('/Catégorie/add', name: 'categorie_add')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        
        $c = new Categorie();
        $form = $this->createForm(CategorieType::class,$c);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $c = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($c);
            $em->flush();
        }
        
        return $this->redirectToRoute('categorie_liste');
    }
}
