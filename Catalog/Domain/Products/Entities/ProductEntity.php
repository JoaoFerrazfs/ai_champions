<?php

namespace Catalog\Domain\Products\Entities;

use DateTimeImmutable;


class ProductEntity
{


    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?DateTimeImmutable $updatedAt = null,
        public readonly ?string $id = null,
    ){
    }
}
