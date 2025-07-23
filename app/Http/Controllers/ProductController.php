<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Retrieves a paginated list of products, with optional category filtering.
    public function index(Request $request)
    {
        // Determines the number of items per page, defaulting to 8.
        $perPage = $request->get('per_page', 8);
        // Retrieves the category ID from the request, if provided.
        $categoryId = $request->get('category_id');

        // Starts a query for products, eager-loading the associated category.
        $query = Product::with('category');

        // Applies a category filter if a category ID is provided.
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Paginates the results based on the determined per page value.
        $products = $query->paginate($perPage);

        // Returns the paginated product data as a JSON response.
        return response()->json([
            'data' => $products->items(), // The actual product items for the current page.
            'current_page' => $products->currentPage(), // The current page number.
            'per_page' => $products->perPage(), // The number of items per page.
            'total' => $products->total(), // The total number of items across all pages.
            'last_page' => $products->lastPage(), // The last page number.
            'has_more' => $products->hasMorePages() // Indicates if there are more pages available.
        ]);
    }

    // Retrieves a list of all product categories.
    public function categories()
    {
        // Fetches all categories from the database.
        $categories = Category::all();

        // Returns the categories data as a JSON response.
        return response()->json([
            'data' => $categories
        ]);
    }
}
