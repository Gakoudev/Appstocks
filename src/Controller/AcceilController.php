<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceilController extends AbstractController
{
    #[Route('/acceil', name: 'acceil')]
    public function index(): Response
    {
        return $this->render('acceil.html.twig');
    }
}
