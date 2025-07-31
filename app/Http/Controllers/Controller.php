<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Show all baskets for admin dashboard with optional is_draft filter.
     */
    public function adminBaskets(Request $request)
    {
        $query = Basket::with('user');
        if ($request->filled('is_draft')) {
            $query->where('is_draft', $request->is_draft);
        }
        $baskets = $query->orderByDesc('created_at')->paginate(15);
        // No change needed for view, as is_draft is now integer (1/0). If you want to ensure boolean in view, you can cast in the view: (bool)$basket->is_draft
        return view('admin.baskets', compact('baskets'));
    }
}
