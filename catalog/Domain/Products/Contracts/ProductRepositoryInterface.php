<?php

namespace Catalog\Domain\Products\Contracts;

use Catalog\Domain\Products\Entities\ProductEntity;

interface ProductRepositoryInterface
{
    public function create(ProductEntity $product): ?ProductEntity;
}
