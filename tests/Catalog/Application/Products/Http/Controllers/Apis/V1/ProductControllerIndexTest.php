<?php

namespace Tests\Catalog\Application\Products\Http\Controllers\Apis\V1;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use Catalog\Application\Products\Http\Controllers\Apis\V1\ProductController;
use Catalog\Application\Products\Service\ProductService;
use Catalog\Domain\Products\Entities\ProductEntity;

class ProductControllerIndexTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndexReturnsPaginatedData()
    {
        $service = Mockery::mock(ProductService::class);

        $items = [];
        for ($i = 1; $i <= 10; $i++) {
            $items[] = new ProductEntity('P'.$i, 'D', 1.0 * $i, null, null, 'id-'.$i);
        }

        $meta = ['current_page' => 1, 'per_page' => 10, 'total' => 15, 'last_page' => 2];

        $service->expects()->paginate(1)->andReturn(['items' => $items, 'meta' => $meta]);

        $controller = new ProductController($service);

        $request = Request::create('/', 'GET', ['page' => 1]);

        $response = $controller->index($request);

        $this->assertSame(200, $response->getStatusCode());

        $payload = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('meta', $payload);
        $this->assertCount(10, $payload['data']);
        $this->assertSame($meta, $payload['meta']);
    }
}
