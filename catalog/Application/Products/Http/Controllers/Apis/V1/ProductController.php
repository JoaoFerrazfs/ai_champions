<?php

namespace Catalog\Application\Products\Http\Controllers\Apis\V1;

use Catalog\Application\Products\Http\Request\ProductRequest;
use Catalog\Application\Products\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
}
