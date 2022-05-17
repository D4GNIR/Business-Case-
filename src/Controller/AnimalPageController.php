<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalPageController extends AbstractController
{
    #[Route('/animal/page', name: 'app_animal_page')]
    public function index(): Response
    {
        return $this->render('animal_page/index.html.twig', [
            'controller_name' => 'AnimalPageController',
        ]);
    }
}
