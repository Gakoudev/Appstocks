<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Produit;
use App\Form\SortieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/Sortie/liste', name: 'sortie_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $s= new Sortie();
        $form = $this->createForm(SortieType::class, $s, array('action'=>$this->generateUrl('sortie_add')));
        $data['form'] = $form->createView();
        $data['sorties']= $em->getRepository(Sortie::class)->findAll();
        
        return $this->render('sortie/liste.html.twig',$data);
    }

    #[Route('/Sortie/add', name: 'sortie_add')]
    public function add(Sortie $s, ManagerRegistry $doctrine, Request $request): Response
    {
        $s = new Sortie();
        $p = new Produit();
        $form = $this->createForm(SortieType::class,$s);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $s = $form->getData();
            $s->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($s);
            $p = $em->getRepository(Produit::class)->find($s->getProduit()->getId());
            $stock = $p->getQtStock() - $s->getQtS();
            if ($stock>=0) {
                $em->flush();
                //mise à jours produit
                
                $p->setQtStock($stock);
    
                $em->flush();
                return $this->redirectToRoute('sortie_liste');
            }
            else {
                
                $form = $this->createForm(SortieType::class, $s, array('action'=>$this->generateUrl('sortie_add')));
                $data['form'] = $form->createView();
                $data['sorties']= $em->getRepository(Sortie::class)->findAll();
                $data['error_message'] ='le stock disponible est inférieur à '.$s->getQtS();
                return $this->render('sortie/liste.html.twig',$data);
            }
           
        }
    }
        
}
