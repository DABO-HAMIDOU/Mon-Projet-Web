<?php

namespace App\Service\Cart;

use App\Service\Cart\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {}

    public function getCart(): array
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    public function setCart(array $cart): self
    {
        $this->requestStack->getSession()->set('cart', $cart);
        return $this;
    }

    public function add(int $id): void
    {
        // Récupérons le panier
        $cart = $this->getCart();

        // Si le produit que je tente d'ajouter au panier existe dejà
        if (\array_key_exists($id, $cart)) {
            // Incrémente sa quantité de 1
            $cart[$id]++;
        } else {
            // Dnas le cas contraire, initialise sa quantité à 1
            $cart[$id] = 1;
        }

        // Mets à jour le panier
        $this->setCart($cart);
    }


    public function getCartItems(): array
    {
        // Récupérer le panier
        $cart = $this->getCart();

        $cartItems = [];

        // En parcourant le tableau du panier,
        // Récupérons chaque produit correspondant à son identifiant
        // Puis, sauvegardons ce produit ainsi que sa quantité dans le tableau des items
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if (null === $product) {
                continue;
            }

            $cartItems[] = new CartItem($product, $quantity);
        }

        return $cartItems;
    }


    public function getCartTotalAmount(): float
    {
        $cartItems = $this->getCartItems();

        $totalAmount = 0;

        foreach ($cartItems as $cartItem) {
            $totalAmount += $cartItem->getAmount();
        }

        return $totalAmount;
    }


    public function decrement(int $id): void
    {
        $cart = $this->getCart();

        // Si le produit n'existe pas dans le panier
        // On ne fait rien
        if (! \array_key_exists($id, $cart)) {
            return;
        }

        // Si la quantité du produit est à 1
        // Retirer le produit du panier
        if ($cart[$id] == 1) {
            $this->remove($id);
            return;
        }

        // Dans le cas contraire
        // Décrémenter le produit de 1
        $cart[$id]--;

        $this->setCart($cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->setCart($cart);
    }
}
