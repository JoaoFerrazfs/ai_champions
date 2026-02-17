<?php
// Este arquivo deve ser usado pra criar um produtos usando a classe ProductRepository

use Catalog\Domain\Products\Entities\ProductEntity;
use Catalog\Infrastructure\Products\Repositories\ProductRepository;
use DateTimeImmutable;


$productRepository = app()->make(ProductRepository::class);
$productRepository->create(new ProductEntity(
    'Produto Teste',
   'Descrição do produto teste',
  99.99,
  new DateTimeImmutable(),
  new DateTimeImmutable()
));