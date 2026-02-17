<?php

use Illuminate\Support\Facades\Route;
use Catalog\Application\Products\Http\Controllers\Apis\V1\ProductController;

Route::prefix('v1/catalog/products')->group(function () {
    Route::post('/', [ProductController::class, 'create'])->name('catalog.products.create');
});
