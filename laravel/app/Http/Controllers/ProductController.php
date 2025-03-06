<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Get all products - GET /api/products
    public function getProducts(): JsonResponse
    {
        $products = Product::all();

        return response()->json([
            "message" => "List of products",
            "data" => $products
        ], 200);
    }

    // Create a new product - POST /api/products
    public function createProduct(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id', // ✅ Fixed table name
            'description' => 'nullable|string',
            'images' => 'nullable|json',
        ]);

        $product = Product::create($validated);

        return response()->json([
            "message" => "Product created successfully",
            "data" => $product
        ], 201);
    }

    // Get a specific product - GET /api/products/{productId}
    public function getProduct($productId): JsonResponse
    {
        $product = Product::findOrFail($productId);

        return response()->json([
            "message" => "Product retrieved successfully",
            "data" => $product
        ], 200);
    }

    // Update a product - PATCH /api/products/{productId}
    public function updateProduct(Request $request, $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'nullable|string',
            'images' => 'nullable|json',
        ]);

        $product->update($validated);

        return response()->json([
            "message" => "Product updated successfully",
            "data" => $product
        ], 200);
    }

    // Delete a product - DELETE /api/products/{productId}
    public function deleteProduct($productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        $product->delete();

        return response()->json([
            "message" => "Product deleted successfully"
        ], 200);
    }
}
