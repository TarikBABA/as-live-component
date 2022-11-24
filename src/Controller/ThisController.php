<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThisController extends AbstractController
{
    #[Route('/', name: 'app_this')]
    public function index(): Response
    {
        return $this->render('this/index.html.twig', [
            'controller_name' => 'ThisController',
        ]);
    }
}