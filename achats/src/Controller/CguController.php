<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CguController extends AbstractController
{
    #[Route('/conditions-generales-utilisations', name: 'app_cgu')]
    public function index(): Response
    {
        
        return $this->render('cgu/index.html.twig', [
            'controller_name' => 'CguController',
        ]);
    }
}
