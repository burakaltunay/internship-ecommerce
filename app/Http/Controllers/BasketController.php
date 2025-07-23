<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BasketConfirmed;
use App\Mail\BasketRejected;
use App\Mail\BasketDelivered;

class BasketController extends Controller
{
    /**
     * Sepeti onayla ve veritabanına kaydet
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total_price' => 'required|numeric|min:0',
            'email' => 'required|email|max:255',
        ]);

        Log::info('Validation passed', [
            'data' => $request->all(),
            'items_count' => count($request->items),
            'total_price' => $request->total_price,
            'email' => $request->email
        ]);

        try {
            // Veritabanına kaydet
            $basket = Basket::create([
                'items' => $request->items,
                'total_price' => $request->total_price,
                'email' => $request->email,
                'is_draft' => 1, // Sepet oluşturulunca draft
                'is_approved' => 0,
                'is_delivered' => 0
            ]);

            Log::info('Basket created successfully', [
                'basket_id' => $basket->id,
                'email' => $basket->email,
                'total_price' => $basket->total_price
            ]);

            // Email gönder (onay maili) kaldırıldı, sadece admin bildirimi kaldı

            // Admin'e de bilgi emaili gönder kaldırıldı

            return response()->json([
                'success' => true,
                'message' => 'Basket confirmed successfully!',
                'basket' => [
                    'id' => $basket->id,
                    'items' => $basket->items,
                    'total_price' => $basket->total_price,
                    'email' => $basket->email,
                    'is_draft' => $basket->is_draft == 1 ? true : false,
                    'is_approved' => $basket->is_approved,
                    'is_delivered' => $basket->is_delivered,
                    'created_at' => $basket->created_at,
                    'updated_at' => $basket->updated_at
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Basket confirmation failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm basket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Basket detaylarını getir
     */
    public function show($id)
    {
        try {
            $basket = Basket::findOrFail($id);
            
            // Product names'i al
            $productIds = collect($basket->items)->pluck('product_id')->unique();
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            // Items'a product name'leri ekle
            $basket->items = collect($basket->items)->map(function($item) use ($products) {
                $product = $products->get($item['product_id']);
                $item['product_name'] = $product ? $product->name : 'Bilinmeyen Ürün';
                $item['product_price'] = $product ? $product->price : 0;
                return $item;
            })->toArray();
            
            return response()->json([
                'success' => true,
                'basket' => [
                    'id' => $basket->id,
                    'items' => $basket->items,
                    'total_price' => $basket->total_price,
                    'email' => $basket->email,
                    'is_draft' => $basket->is_draft == 1 ? true : false,
                    'is_approved' => $basket->is_approved,
                    'is_delivered' => $basket->is_delivered,
                    'created_at' => $basket->created_at,
                    'updated_at' => $basket->updated_at
                ]
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Basket not found', [
                'basket_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Basket not found'
            ], 404);
        }
    }

    /**
     * Simulate payment and update basket status
     */
    public function simulatePayment(Request $request, $id)
    {
        $basket = Basket::findOrFail($id);
        // Only allow if not already approved
        if ($basket->is_approved || $basket->is_delivered) {
            return response()->json([
                'success' => false,
                'message' => 'Basket already approved or delivered.'
            ], 400);
        }

        // Simulate payment: 60% success, 40% fail
        $success = mt_rand(1, 100) <= 60;
        //$success = false;

        if ($success) {
            $basket->is_draft = 0;
            $basket->is_approved = 1;
            $basket->save();
            // Sepet onaylandığında mail gönder
            try {
                Mail::to($basket->email)->send(new BasketConfirmed($basket));
            } catch (\Exception $e) {
                Log::error('BasketConfirmed mail failed', ['error' => $e->getMessage(), 'basket_id' => $basket->id]);
            }
            // Schedule delivery update after 1 minute
            \App\Jobs\SendBasketFollowupEmail::dispatch($basket)->delay(now()->addMinute());
            return response()->json([
                'success' => true,
                'message' => 'Payment successful. Basket approved.',
                'basket' => [
                    'id' => $basket->id,
                    'is_draft' => false,
                    'is_approved' => 1,
                    'is_delivered' => $basket->is_delivered
                ]
            ]);
        } else {
            $basket->is_approved = 0;
            $basket->is_delivered = 0;
            $basket->save();
            // Sepet onaylanmadığında mail gönder
            try {
                Mail::to($basket->email)->send(new BasketRejected($basket));
            } catch (\Exception $e) {
                Log::error('BasketRejected mail failed', ['error' => $e->getMessage(), 'basket_id' => $basket->id]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Payment failed. Please try again.',
                'basket' => [
                    'id' => $basket->id,
                    'is_draft' => $basket->is_draft == 1 ? true : false,
                    'is_approved' => 0,
                    'is_delivered' => 0
                ]
            ], 402);
        }
    }
} 