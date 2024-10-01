<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/product', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/product', [ProductController::class, 'store'])->name('products.store');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product/{product:slug}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::patch('/product/{product:slug}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/product/{product:slug}', [ProductController::class, 'destroy'])->name('products.destroy');