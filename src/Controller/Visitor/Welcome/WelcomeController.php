<?php

namespace App\Controller\Visitor\Welcome;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_welcome_visitor_index',methods:['GET'])]
    public function index(): Response
    {

        return $this->render('pages/visitor/welcome/index.html.twig');
    }

    #[Route('/test', name: 'app_test_visitor_index', methods: ['GET'])]
    public function indextest(): Response
    {

        return $this->render('pages/visitor/stage/index.html.twig');
    }
    
    /*
    public function indextest(): Response
    {

        return $this->render('pages/visitor/stage/index.html.twig');
    }*/
}

