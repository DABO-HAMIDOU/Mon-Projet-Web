<?php

namespace App\Controller\Admin\Product;

use DateTimeImmutable;
use App\Entity\Product;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin')]
class ProductController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/product/index', name: 'admin_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('pages/admin/product/index.html.twig', [
            "products" => $productRepository->findAll()
        ]);
    }


    #[Route('/product/create', name: 'admin_product_create', methods: ['GET', 'POST'])]
    function create(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new DateTimeImmutable());
            $product->setUpdatedAt(new DateTimeImmutable());

            $product->setUser($this->getUser());

            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash("succes", "le produit a été ajouter avec succés");
            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render(
            'pages/admin/product/create.html.twig',
            [
                "form" => $form->createView(),

            ]
        );
    }

    #[Route('/product/{id<\d+>}/edit', name: 'admin_product_edit', methods: ['GET', 'POST'])]
    function edit(Product $product, Request $request): Response
    {

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setUpdatedAt(new DateTimeImmutable());

            $product->setUser($this->getUser());

            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash("succes", "le produit a été Modifier  avec succés");
            return $this->redirectToRoute('admin_product_index');
        }
        return $this->render('pages/admin/product/edit.html.twig', [
            "form" => $form->createView(),
            "product" => $product
        ]);
    }

    #[Route('/product/{id<\d+>}/delete', name: 'admin_product_delete', methods: ['DELETE'])]
    function delete(Product $product, Request $request): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid("delete_Product_" . $product->getId(), $request->request->get('_csrf_token'))) {
            // Suppression de la catégorie
            $this->em->remove($product);
            $this->em->flush();  // Correction ici : pas besoin de paramètre dans flush()

            // Message flash de succès
            $this->addFlash("success", "Le produit a été supprimée avec succès !");
        } else {
            // Si le token est invalide, on pourrait ajouter un message d'erreur
            $this->addFlash("error", "Token CSRF invalide. Suppression échouée.");
        }

        // Redirection après suppression
        return $this->redirectToRoute("admin_category_index");
    }
}
