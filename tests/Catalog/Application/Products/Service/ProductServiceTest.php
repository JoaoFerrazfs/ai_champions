<?php

namespace Tests\Catalog\Application\Products\Service;

use Tests\TestCase;
use Mockery;
use Catalog\Application\Products\Service\ProductService;
use Catalog\Domain\Products\Contracts\ProductRepositoryInterface;
use Catalog\Domain\Products\Entities\ProductEntity;

class ProductServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateCallsRepositoryWithConvertedPrice()
    {
        // Set
        $data = [
            'name' => 'Product A',
            'description' => 'Desc',
            'price' => '12.34',
        ];
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $service = new ProductService($repo);

        // Expectations
        $expected = new ProductEntity('Product A', 'Desc', 12.34);
        $repo->expects('create')
            ->with(Mockery::type(ProductEntity::class))
            ->andReturn($expected);

        // Actions
        $result = $service->create($data);

        // Assertions
        $this->assertInstanceOf(ProductEntity::class, $result);
        $this->assertSame(12.34, $result->price);
    }

    public function testCreateHandlesNonNumericPrice()
    {
        // Set
        $data = [
            'name' => 'Product B',
            'description' => 'Desc',
            'price' => 'abc',
        ];
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $service = new ProductService($repo);

        // Expectations
        $expected = new ProductEntity('Product B', 'Desc', 0.0);
        $repo->expects('create')
            ->with(Mockery::type(ProductEntity::class))
            ->andReturn($expected);

        // Actions
        $result = $service->create($data);

        // Assertions
        $this->assertInstanceOf(ProductEntity::class, $result);
        $this->assertSame(0.0, $result->price);
    }
}
