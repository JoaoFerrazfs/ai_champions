<?php

namespace Catalog\Application\Products\Service;

use Catalog\Domain\Products\Contracts\ProductRepositoryInterface;
use Catalog\Domain\Products\Entities\ProductEntity;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function create(array $data): ?ProductEntity
    {
        $price = is_numeric($data['price']) ? (float) $data['price'] : 0.0;

        $product = new ProductEntity(
            name: $data['name'],
            description: $data['description'],
            price: $price,
        );

        return $this->productRepository->create(product: $product);
    }
}
