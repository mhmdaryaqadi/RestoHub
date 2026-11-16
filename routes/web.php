<?php

use App\Http\Controllers\Customer\MenuCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/order', [MenuCatalogController::class, 'index'])->name('customer.catalog');
