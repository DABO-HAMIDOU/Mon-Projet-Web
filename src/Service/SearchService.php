<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\ProductRepository;

class SearchService
{
    public function __construct(private ProductRepository $productRepository) {}

    public function searchProducts(?Category $category, ?string $keywords): array
    {
        return $this->productRepository->findProductsSearched($category, $keywords);
    }
}
