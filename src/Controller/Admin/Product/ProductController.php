<?php
namespace App\Controller\Admin\Product;



use App\Entity\Product;
use App\Form\ProductFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class ProductController extends AbstractController{

    #[Route('/product/index', name: 'admin_product_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/product/index.html.twig');
    }
    
    
    #[Route('/product/create', name: 'admin_product_create', methods:['GET', 'POST'])]
    function create(Request $request):Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
           
        }
        
        return $this->render(
            'pages/admin/product/create.html.twig',[
                "form" => $form->createView(),
            
            ]);
    }
}
