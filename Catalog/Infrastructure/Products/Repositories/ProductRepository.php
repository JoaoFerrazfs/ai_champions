<?php

namespace Catalog\Infrastructure\Products\Repositories;

use Catalog\Domain\Products\Entities\ProductEntity;
use Illuminate\Contracts\Container\Container;
use Catalog\Infrastructure\Products\Models\ProductModel;
use Catalog\Domain\Products\Contracts\ProductRepositoryInterface;
use DateTimeImmutable;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Container $container
    ) {}

    public function create(ProductEntity $product): ?ProductEntity
    {

        $model = $this->model();

        $model->name = $product->name;
        $model->description = $product->description;
        $model->price = $product->price;

        if(!$result = $model->save()){
            return null;
        }

        return new ProductEntity(
            $model->name,
            $model->description,
            (float) $model->price,
            new DateTimeImmutable($model->created_at),
            new DateTimeImmutable($model->updated_at),
            $model->id
        );
    }
    private function model(): ProductModel
    {
        return $this->container->make(ProductModel::class);
    }
}
