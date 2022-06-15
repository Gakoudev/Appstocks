<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EntreeController extends AbstractController
{
    #[Route('/Entree/liste', name: 'entree_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $e= new Entree();
        $form = $this->createForm(EntreeType::class, $e, array('action'=>$this->generateUrl('entree_add')));
        $data['form'] = $form->createView();
        $data['entrees']= $em->getRepository(Entree::class)->findAll();
        
        return $this->render('entree/liste.html.twig',$data);
    }

    #[Route('/Entree/add', name: 'entree_add')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $e = new Entree();
        $p = new Produit();
        $form = $this->createForm(EntreeType::class,$e);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $e = $form->getData();
            $e->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($e);
            $em->flush();
            //mise à jours produit
            $p = $em->getRepository(Produit::class)->find($e->getProduit()->getId());
            $stock = $p->getQtStock() + $e->getQtE();
            $p->setQtStock($stock);

            $em->flush();
        }
        
        return $this->redirectToRoute('entree_liste');
    }
}
