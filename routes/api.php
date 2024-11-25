<?php

declare(strict_types=1);

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('/orders', [OrderController::class, 'transform'])->name('orders.transform');
});
