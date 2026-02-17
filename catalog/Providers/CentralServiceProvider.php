<?php

namespace Catalog\Providers;

use Illuminate\Support\ServiceProvider;

class CentralServiceProvider extends ServiceProvider
{

    protected $providers = [
        RepositoriesProvider::class,
    ];

    public function register(): void
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }

        $this->app->singleton('catalog.central', function ($app): CentralServiceProvider {
            return $this;
        });
    }

    public function boot(): void
    {

        $candidates = [
            __DIR__ . '/../Routes/api.php',
            __DIR__ . '/../Application/Routes/api.php',
        ];

        foreach ($candidates as $routesPath) {
            if (file_exists($routesPath)) {
                $this->loadRoutesFrom($routesPath);
                break;
            }
        }
    }

}
