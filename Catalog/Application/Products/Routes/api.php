<?php

use Illuminate\Support\Facades\Route;
use Catalog\Application\Products\Http\Controllers\Apis\V1\ProductController;

Route::middleware(['api'])->group(function () {
    Route::get('v1/catalog/products', [ProductController::class, 'index']);
});
