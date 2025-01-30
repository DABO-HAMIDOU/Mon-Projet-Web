<?php

namespace App\Controller\Visitor\AboutUs;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutUsController extends AbstractController
{
    #[Route('/about-us', name:'visitor_about_us_index',methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/about_us/index.html.twig');
    }
}
