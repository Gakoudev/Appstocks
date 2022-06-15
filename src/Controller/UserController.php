<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/User/liste', name: 'user_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $u= new User();
        $form = $this->createForm(UserType::class, $u, array('action'=>$this->generateUrl('user_add')));
        $form->remove('password');
        $data['form'] = $form->createView();
        $data['users']= $em->getRepository(User::class)->findAll();
        return $this->render('user/liste.html.twig', $data);
    }
    //
    #[Route('/User/editPassword', name: 'user_editPassword')]
    public function edit(): Response
    {

        $u= new User();
        $form = $this->createForm(UserType::class, $u, array('action'=>$this->generateUrl('user_updatePassword')));
        $form->remove('email');
        $form->remove('prenom');
        $form->remove('nom');
        $form->remove('roles');
        $data['form'] = $form->createView();
        return $this->render('user/edit.html.twig', $data);
    }  
    
    
    #[Route('/User/updatePassword', name: 'user_updatePassword')]
    public function updatePassword(ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $u = new User();
        $form = $this->createForm(UserType::class,$u);
        $form->handleRequest($request);
        //hash du mot de passe
        if ($form->isSubmitted() && $form->isValid()) {
            $u = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $u,
                $u->getPassword()
            );
            $this->getUser()->setPassword($hashedPassword);
            $em = $doctrine->getManager();
            $em->persist($this->getUser());
            $em->flush();
        }
        $message = "Mot de Passe modifier avec succes";
        $this->addFlash('succes',$message);
        return $this->redirectToRoute('acceil');
    }
    

    #[Route('/User/add', name: 'user_add')]
    public function add(ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $u = new User();
        $form = $this->createForm(UserType::class,$u);
        $form->handleRequest($request);
        //hash du mot de passe
        if ($form->isSubmitted() && $form->isValid()) {
            $u = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $u,
                "passer"
            );
            $u->setPassword($hashedPassword);
            $em = $doctrine->getManager();
            $em->persist($u);
            $em->flush();
        }
        
        return $this->redirectToRoute('user_liste');
    }
}
