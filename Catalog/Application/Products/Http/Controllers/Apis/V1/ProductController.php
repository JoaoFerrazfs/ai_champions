<?php

namespace Catalog\Application\Products\Http\Controllers\Apis\V1;

use Catalog\Application\Products\Http\Request\ProductRequest;
use Catalog\Application\Products\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Catalog\Application\Products\Http\Resources\ProductResource;

class ProductController
{
    public function __construct(
        public readonly ProductService $productService,
    ){
    }

    public function create(ProductRequest $request): Response
    {   
        if($result = $this->productService->create($request->all())) {
            return new JsonResponse($result, Response::HTTP_CREATED);
        }
        
        return new JsonResponse($result, Response::HTTP_BAD_REQUEST);
    }

    public function index(Request $request): Response
    {
        $page = (int) $request->query('page', 1);

        $result = $this->productService->paginate($page);

        $items = array_map(fn($entity) => ProductResource::toArray($entity), $result['items']);

        return new JsonResponse([
            'data' => $items,
            'meta' => $result['meta'],
        ], Response::HTTP_OK);
    }
}
