<?php

namespace Catalog\Domain\Products\Entities;

use DateTimeImmutable;


class ProductEntity
{


    public function __construct(
    public readonly string $name,
    public readonly string $description,
    public readonly float $price,
    public readonly DateTimeImmutable $createdAt,
    public readonly DateTimeImmutable $updatedAt,
    public readonly ?string $id = null,

    ){
    }
}
