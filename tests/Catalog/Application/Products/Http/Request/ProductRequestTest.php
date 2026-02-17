<?php

namespace Tests\Catalog\Application\Products\Http\Request;

use Tests\TestCase;
use Catalog\Application\Products\Http\Request\ProductRequest;

class ProductRequestTest extends TestCase
{
    public function testRulesStructure()
    {
        // Set
        $request = new ProductRequest();

        // Actions
        $rules = $request->rules();

        // Assertions
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('price', $rules);
    }
}
