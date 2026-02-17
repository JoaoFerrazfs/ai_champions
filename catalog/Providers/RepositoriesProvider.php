<?php

namespace Catalog\Providers;

use Catalog\Domain\Products\Contracts\ProductRepositoryInterface;
use Catalog\Infrastructure\Products\Repositories\ProductRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider  extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class,
        fn () => $this->app->make(ProductRepository::class)
        );
    }

    public function provides(): array
    {
        return [ProductRepositoryInterface::class];
    }
}
