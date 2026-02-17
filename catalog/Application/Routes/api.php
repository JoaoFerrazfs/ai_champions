<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    require __DIR__ . '/Products/api.php';
});
