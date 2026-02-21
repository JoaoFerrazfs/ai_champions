<?php

namespace Catalog\Application\Products\Http\Resources;

use Catalog\Domain\Products\Entities\ProductEntity;

class ProductResource
{
    public static function toArray(ProductEntity $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'created_at' => $product->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $product->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
