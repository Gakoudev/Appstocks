<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RolesController extends AbstractController
{
    #[Route('/Roles/liste', name: 'roles_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $r= new Roles();
        $data['roles']= $em->getRepository(Roles::class)->findAll();
        return $this->render('roles/liste.html.twig', $data);
        
    }
    
}
