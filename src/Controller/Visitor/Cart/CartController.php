<?php

namespace App\Controller\Visitor\Cart;

use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartService $cartService
    ) {}

    #[Route('/cart', name: 'visitor_cart_index', methods: ['GET'])]
    public function index(): Response
    {

       
        return $this->render('pages/visitor/cart/index.html.twig', [
            "cartItems"     => $this->cartService->getCartItems(),
            "cartTotalAmount"   => $this->cartService->getCartTotalAmount()
            
        ]);
    }


    #[Route('/cart/{id}/add', name: 'visitor_cart_add', methods: ['GET'])]
    public function add(string $id): Response
    {

        $product = $this->productRepository->find((int) $id);

        if (null === $product) {
            throw $this->createNotFoundException("The product with id={$id} not found.");
        }

        if ($product->getQuantity() <= 0) {
            throw $this->createNotFoundException("The product with id={$id} is not available.");
        }

        $this->cartService->add((int) $id);

        return $this->redirectToRoute("visitor_cart_index");
    }


    #[Route('/cart/{id}/decrement', name: 'visitor_cart_decrement', methods: ['GET'])]
    public function decrement(string $id): Response
    {
        $product = $this->productRepository->find((int) $id);

        if (null === $product) {
            throw $this->createNotFoundException("The product with id={$id} not found.");
        }

        if ($product->getQuantity() <= 0) {
            throw $this->createNotFoundException("The product with id={$id} is not available.");
        }

        $this->cartService->decrement((int) $id);

        return $this->redirectToRoute("visitor_cart_index");
    }


    #[Route('/cart/{id}/remove', name: 'visitor_cart_remove', methods: ['GET'])]
    public function remove(string $id): Response
    {
        $product = $this->productRepository->find((int) $id);

        if (null === $product) {
            throw $this->createNotFoundException("The product with id={$id} not found.");
        }

        if ($product->getQuantity() <= 0) {
            throw $this->createNotFoundException("The product with id={$id} is not available.");
        }

        $this->cartService->remove((int) $id);

        return $this->redirectToRoute("visitor_cart_index");
    }
}
