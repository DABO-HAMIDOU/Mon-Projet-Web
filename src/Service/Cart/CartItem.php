<?php
namespace App\Service\cart;




use App\Entity\Product;

class CartItem
{
    public function __construct(
        public Product $product,
        public int $quantity
    ) {}

    public function getAmount(): float
    {
        return $this->product->getSellingPrice() * $this->quantity;
    }
}
