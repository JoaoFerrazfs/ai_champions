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

    public function testIndexReturns200AndTransformedItemsWithPageParam()
    {
        // Set
        $page = 2;
        $entity = new ProductEntity('P','D',10.0,new \DateTimeImmutable('2026-01-01 10:00:00'), new \DateTimeImmutable('2026-01-01 11:00:00'), 'id-1');
        $service = Mockery::mock(ProductService::class);
        $request = new \Illuminate\Http\Request(['page' => $page]);
        $controller = new ProductController($service);

        $expectedMeta = ['page' => $page, 'per_page' => 15, 'total' => 1];

        // Expectations
        $service->expects()
            ->paginate($page)
            ->andReturn(['items' => [$entity], 'meta' => $expectedMeta]);

        // Actions
        $response = $controller->index($request);

        // Assertions
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('meta', $content);
        $this->assertSame($expectedMeta, $content['meta']);
        $this->assertSame(
            \Catalog\Application\Products\Http\Resources\ProductResource::toArray($entity),
            $content['data'][0]
        );
    }

    public function testIndexReturns200AndUsesDefaultPageWhenNotProvided()
    {
        // Set
        $defaultPage = 1;
        $entity = new ProductEntity('X','Y',5.5,null,null,'id-2');
        $service = Mockery::mock(ProductService::class);
        $request = new \Illuminate\Http\Request();
        $controller = new ProductController($service);

        $expectedMeta = ['page' => $defaultPage, 'per_page' => 15, 'total' => 1];

        // Expectations
        $service->expects()
            ->paginate($defaultPage)
            ->andReturn(['items' => [$entity], 'meta' => $expectedMeta]);

        // Actions
        $response = $controller->index($request);

        // Assertions
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertSame($expectedMeta, $content['meta']);
        $this->assertSame(
            \Catalog\Application\Products\Http\Resources\ProductResource::toArray($entity),
            $content['data'][0]
        );
    }
}
