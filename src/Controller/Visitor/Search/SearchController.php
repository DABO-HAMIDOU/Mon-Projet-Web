<?php
namespace App\Controller\Visitor\Search;


use App\Form\SearchProductsFormType;
use App\Repository\ProductRepository;
use App\Service\SearchService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods:['GET'])]
    public function index(
        Request $request,
        SearchService $searchService,
        ProductRepository $productRepository
    ): Response
    {
        $form = $this->createForm(SearchProductsFormType::class);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            
            $data = $form->getData();

            $products = $searchService->searchProducts($data['category'], $data['keywords']); 

          

            return $this->render('pages/visitor/search/index.html.twig', [
                'products' => $products,
            ]);
        }

        return $this->redirectToRoute("visitor_catalog_index", [
            "products" => $productRepository->findBy(["isAvailable" => true])
        ]);
    }



    public function getSearchBar(): Response
    {
        $form = $this->createForm(SearchProductsFormType::class);

        return $this->render("components/_search_bar.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
