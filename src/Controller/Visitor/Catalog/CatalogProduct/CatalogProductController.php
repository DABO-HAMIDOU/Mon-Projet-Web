<?php

namespace App\Controller\Visitor\Catalog\CatalogProduct;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatalogProductController extends AbstractController
{
    #[Route('/catalog', name: 'catalog_product_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/Visitor/catalog/index.html.twig');
    }
}
