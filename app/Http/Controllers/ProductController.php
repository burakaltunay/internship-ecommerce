<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 8);
        $categoryId = $request->get('category_id');

        $query = Product::with('category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate($perPage);

        return response()->json([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
            'has_more' => $products->hasMorePages()
        ]);
    }

    public function categories()
    {
        $categories = Category::all();

        return response()->json([
            'data' => $categories
        ]);
    }
}
