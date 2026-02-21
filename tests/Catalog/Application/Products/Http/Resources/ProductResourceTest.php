<?php

namespace Tests\Catalog\Application\Products\Http\Resources;

use PHPUnit\Framework\TestCase;
use Catalog\Application\Products\Http\Resources\ProductResource;
use Catalog\Domain\Products\Entities\ProductEntity;

class ProductResourceTest extends TestCase
{
    public function testToArrayContainsExpectedKeys()
    {
        $entity = new ProductEntity('Name', 'Desc', 9.99, null, null, 'uuid-1');

        $array = ProductResource::toArray($entity);

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('price', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('updated_at', $array);

        $this->assertSame('uuid-1', $array['id']);
        $this->assertSame('Name', $array['name']);
        $this->assertSame(9.99, $array['price']);
    }
}
