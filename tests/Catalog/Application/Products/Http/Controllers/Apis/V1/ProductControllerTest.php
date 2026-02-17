<?php

namespace Tests\Catalog\Application\Products\Http\Controllers\Apis\V1;

use Tests\TestCase;
use Mockery;
use Catalog\Application\Products\Http\Controllers\Apis\V1\ProductController;
use Catalog\Application\Products\Service\ProductService;
use Catalog\Application\Products\Http\Request\ProductRequest;
use Catalog\Domain\Products\Entities\ProductEntity;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateReturns201OnSuccess()
    {
        // Set
        $data = ['name' => 'P','description'=>'D','price'=>10.0];
        $entity = new ProductEntity('P','D',10.0);
        $service = Mockery::mock(ProductService::class);
        $request = new ProductRequest();
        $request->merge($data);
        $controller = new ProductController($service);

        // Expectations
        $service->expects()
            ->create($data)
            ->andReturn($entity);

        // Actions
        $response = $controller->create($request);

        // Assertions
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testCreateReturns400OnFailure()
    {
        // Set
        $data = ['name' => 'P','description'=>'D','price'=>10.0];
        $service = Mockery::mock(ProductService::class);
        $request = new ProductRequest();
        $request->merge($data);
        $controller = new ProductController($service);

        // Expectations
        $service->expects()
            ->create($data)
            ->andReturnNull();

        // Actions
        $response = $controller->create($request);

        // Assertions
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
