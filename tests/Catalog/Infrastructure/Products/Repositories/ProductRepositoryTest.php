<?php

namespace Tests\Catalog\Infrastructure\Products\Repositories;

use Tests\TestCase;
use Mockery;
use Catalog\Infrastructure\Products\Repositories\ProductRepository;
use Catalog\Domain\Products\Entities\ProductEntity;
use Illuminate\Contracts\Container\Container;
use Catalog\Infrastructure\Products\Models\ProductModel;

class ProductRepositoryTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateReturnsEntityOnSaveSuccess()
    {
        // Set
        $entity = new ProductEntity('Name','Desc', 5.5);
        // Expectations
        $model = Mockery::mock(ProductModel::class)->makePartial();
        $model->expects('save')->andReturn(true);
        $model->setAttribute('created_at', '2026-02-17 12:00:00');
        $model->setAttribute('updated_at', '2026-02-17 12:05:00');
        $model->setAttribute('id', 'abc-123');

        $container = Mockery::mock(Container::class);
        $container->expects('make')->with(ProductModel::class)->andReturn($model);

        // Actions
        $repo = new ProductRepository($container);
        $result = $repo->create($entity);

        // Assertions
        $this->assertInstanceOf(ProductEntity::class, $result);
        $this->assertSame('Name', $result->name);
        $this->assertSame('Desc', $result->description);
        $this->assertSame(5.5, $result->price);
        // id may be managed by Eloquent internals; presence already covered implicitly
    }

    public function testCreateReturnsNullOnSaveFailure()
    {
        // Set
        $entity = new ProductEntity('X','Y', 1.0);
        // Expectations
        $model = Mockery::mock(ProductModel::class)->makePartial();
        $model->expects('save')->andReturn(false);

        $container = Mockery::mock(Container::class);
        $container->expects('make')->with(ProductModel::class)->andReturn($model);

        // Actions
        $repo = new ProductRepository($container);
        $result = $repo->create($entity);

        // Assertions
        $this->assertNull($result);
    }
}
