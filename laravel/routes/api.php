<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController; // Ensure you have this import at the top
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Group the category-related routes the 'categories'
Route::controller(CategoryController::class)->prefix('categories')->group(function(){
    Route::get('/', 'getCategories');
    Route::post('/','createCategory');
    Route::get('/{categoryId}', 'getCategory');
    Route::patch('/{categoryId}', 'updateCategory');
    Route::delete('/{categoryId}', 'deleteCategory');
});

// Group the product-related routes under the 'products' prefix
Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/', 'getProducts');
    Route::post('/', 'createProduct');
    Route::get('/{productId}', 'getProduct');
    Route::patch('/{productId}', 'updateProduct');
    Route::delete('/{productId}', 'deleteProduct');
});


