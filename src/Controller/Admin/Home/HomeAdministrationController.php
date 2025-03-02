<?php


namespace App\Controller\Admin\Home;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class HomeAdministrationController extends AbstractController
{
    #[Route('/home', name: 'admin_home_index', methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('pages/admin/home/index.html.twig', [
            'controller_name' => 'Admin/Home/HomeController',
        ]);
    }
}
