<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Get all categories - GET /api/categories
    public function getCategories(): JsonResponse
    {
        $categories = Category::all();
        return response()->json([
            "message" => "Getting list of categories",
            "data" => $categories
        ], 200);
    }

    // Create a new category - POST /api/categories
    public function createCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create($validated);

        return response()->json([
            "message" => "Creating a new category",
            "data" => $category
        ], 201);
    }

    // Get a specific category - GET /api/categories/{categoryId}
    public function getCategory($categoryId): JsonResponse
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json([
                "message" => "Category not found"
            ], 404);
        }

        return response()->json([
            "message" => "Getting category based on given categoryId",
            "data" => $category
        ], 200);
    }

    // Update a category - PATCH /api/categories/{categoryId}
    public function updateCategory(Request $request, $categoryId): JsonResponse
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json([
                "message" => "Category not found"
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255'  // allowing partial updates
        ]);

        // Update only if the name is present
        if ($validated['name'] ?? false) {
            $category->update($validated);
        }

        return response()->json([
            "message" => "Category updated",
            "data" => $category
        ], 200);
    }

    // Delete a category - DELETE /api/categories/{categoryId}
    public function deleteCategory($categoryId): JsonResponse
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json([
                "message" => "Category not found"
            ], 404);
        }

        $category->delete();

        return response()->json([
            "message" => "Category deleted successfully"
        ], 200);
    }
}
