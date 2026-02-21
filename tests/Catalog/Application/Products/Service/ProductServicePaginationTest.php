<?php

namespace Tests\Catalog\Application\Products\Service;

use Tests\TestCase;
use Mockery;
use Catalog\Application\Products\Service\ProductService;
use Catalog\Domain\Products\Contracts\ProductRepositoryInterface;
use Catalog\Domain\Products\Entities\ProductEntity;

class ProductServicePaginationTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testPaginateDelegatesToRepository()
    {
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $service = new ProductService($repo);

        $items = [new ProductEntity('P','D',1.0,null,null,'id-1')];
        $meta = ['current_page'=>1,'per_page'=>10,'total'=>1,'last_page'=>1];

        $repo->expects('paginate')->with(10, 1)->andReturn(['items' => $items, 'meta' => $meta]);

        $result = $service->paginate(1);

        $this->assertArrayHasKey('items', $result);
        $this->assertArrayHasKey('meta', $result);
    }
}
