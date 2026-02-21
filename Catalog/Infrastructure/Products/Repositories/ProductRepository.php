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
    public function paginate(int $perPage, int $page = 1): array
    {
        $model = $this->model();

        $paginator = $model->newQuery()->paginate($perPage, ['*'], 'page', $page);

        $items = array_map(function ($m) {
            return new ProductEntity(
                $m->name,
                $m->description,
                (float) $m->price,
                new DateTimeImmutable($m->created_at),
                new DateTimeImmutable($m->updated_at),
                $m->id
            );
        }, $paginator->items());

        $meta = [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
        ];

        return ['items' => $items, 'meta' => $meta];
    }
    private function model(): ProductModel
    {
        return $this->container->make(ProductModel::class);
    }
}
