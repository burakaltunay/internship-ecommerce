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

            // Number artırma işlemi sepet onaylandığında yapılacak

            // İndirim kodu kontrolü ve gönderimi
            // (Bu blok ve içeriği kaldırıldı, kuponsuz formata dönüldü)

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
            // Sepet onaylandığında bu sepette kullanılan indirim kodunu is_used = 1 yap
            $usedDiscount = \App\Models\DiscountCode::where('basket_id', $basket->id)
                ->where('is_used', 0)
                ->first();
            if ($usedDiscount) {
                $usedDiscount->is_used = 1;
                $usedDiscount->save();
                \Log::info('İndirim kodu kullanıldı olarak işaretlendi', [
                    'discount_id' => $usedDiscount->id,
                    'basket_id' => $basket->id,
                    'code' => $usedDiscount->code,
                    'email' => $basket->email
                ]);
            }

            // Sepet onaylandığında number değerini artır ve indirim kodu üretimi
            $user = \App\Models\User::where('email', $basket->email)->first();
            if ($user) {
                $user->number = $user->number + 1;
                
                // Eğer number değeri 2'ye ulaştıysa indirim kodu üret
                if ($user->number === 2) {
                    $code = strtoupper(uniqid('INDIRIM'));
                    try {
                        \Log::info('DiscountCode oluşturuluyor (number=2 olduğunda)', ['email' => $user->email, 'code' => $code]);
                        
                        $discount = new \App\Models\DiscountCode();
                        $discount->email = $user->email;
                        $discount->code = $code;
                        $discount->is_used = 0;
                        $discount->basket_id = $basket->id;
                        $discount->expires_at = now()->addDays(30);
                        $discount->save();
                        
                        \Log::info('DiscountCode başarıyla kaydedildi', [
                            'discount_code_id' => $discount->id, 
                            'email' => $user->email, 
                            'code' => $code,
                            'is_used_after_save' => $discount->is_used
                        ]);
                        
                        // İndirim kodu email olarak gönder
                        try {
                            \Mail::to($user->email)->send(new \App\Mail\DiscountCodeMail($code, $user->email));
                        } catch (\Exception $e) {
                            \Log::error('DiscountCodeMail gönderilemedi', ['error' => $e->getMessage(), 'email' => $user->email]);
                        }
                        
                        // Number'ı sıfırla
                        $user->number = 0;
                    } catch (\Exception $e) {
                        \Log::error('DiscountCode kaydedilemedi', ['error' => $e->getMessage(), 'email' => $user->email, 'code' => $code]);
                    }
                } elseif ($user->number > 2) {
                    // Number 2'den büyükse 1'e ayarla
                    $user->number = 1;
                }
                
                $user->save();
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
                'message' => 'Ödeme başarısız. Lütfen tekrar deneyiniz.',
                'basket' => [
                    'id' => $basket->id,
                    'is_draft' => $basket->is_draft == 1 ? true : false,
                    'is_approved' => 0,
                    'is_delivered' => 0
                ]
            ], 402);
        }
    }

    /**
     * Sepetin toplam fiyatını güncelle (indirimli fiyat için)
     */
    public function updateTotal(Request $request, $id)
    {
        $request->validate([
            'total_price' => 'required|numeric|min:0',
        ]);
        $basket = \App\Models\Basket::findOrFail($id);
        $basket->total_price = $request->total_price;
        $basket->save();
        return response()->json([
            'success' => true,
            'basket' => $basket
        ]);
    }

    /**
     * Kullanıcının siparişlerini getir
     */
    public function myOrders(Request $request)
    {
        $email = auth()->user()->email;
        $filter = $request->get('filter', 'all');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = Basket::where('email', $email)
            ->where('is_approved', 1); // Sadece onaylanmış siparişler

        // Filtreleme
        switch ($filter) {
            case 'approved':
                $query->where('is_approved', 1)->where('is_delivered', 0);
                break;
            case 'delivered':
                $query->where('is_delivered', 1);
                break;
            case 'rejected':
                $query->where('is_approved', 0);
                break;
            case 'with_discount':
                // İndirim kodu kullanılmış siparişler
                $query->whereHas('discountCodes', function($q) {
                    $q->where('is_used', 1);
                });
                break;
            default: // 'all'
                // Tüm onaylanmış siparişler
                break;
        }

        $totalOrders = $query->count();
        $totalAmount = $query->sum('total_price');

        $orders = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Her sipariş için ürün bilgilerini ekle
        foreach ($orders as $order) {
            $productIds = collect($order->items)->pluck('product_id')->unique();
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            $order->items = collect($order->items)->map(function($item) use ($products) {
                $product = $products->get($item['product_id']);
                $item['product_name'] = $product ? $product->name : 'Bilinmeyen Ürün';
                $item['product_price'] = $product ? $product->price : 0;
                return $item;
            })->toArray();

            // İndirim kodu bilgisini ekle
            $order->has_discount = \App\Models\DiscountCode::where('basket_id', $order->id)
                ->where('is_used', 1)
                ->exists();
        }

        return response()->json([
            'success' => true,
            'orders' => $orders,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalOrders,
                'total_pages' => ceil($totalOrders / $perPage),
                'showing' => min($page * $perPage, $totalOrders),
                'total_orders' => $totalOrders
            ],
            'total_amount' => $totalAmount,
            'filter' => $filter
        ]);
    }

    /**
     * Web sayfası için siparişler sayfasını göster
     */
    public function myOrdersWeb(Request $request)
    {
        $email = auth()->user()->email;
        $filter = $request->get('filter', 'all');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = Basket::where('email', $email)
            ->where('is_approved', 1); // Sadece onaylanmış siparişler

        // Filtreleme
        switch ($filter) {
            case 'approved':
                $query->where('is_approved', 1)->where('is_delivered', 0);
                break;
            case 'delivered':
                $query->where('is_delivered', 1);
                break;
            case 'rejected':
                $query->where('is_approved', 0);
                break;
            case 'with_discount':
                // İndirim kodu kullanılmış siparişler
                $query->whereHas('discountCodes', function($q) {
                    $q->where('is_used', 1);
                });
                break;
            default: // 'all'
                // Tüm onaylanmış siparişler
                break;
        }

        $totalOrders = $query->count();
        $totalAmount = $query->sum('total_price');

        $orders = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Her sipariş için ürün bilgilerini ekle
        foreach ($orders as $order) {
            $productIds = collect($order->items)->pluck('product_id')->unique();
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            $order->items = collect($order->items)->map(function($item) use ($products) {
                $product = $products->get($item['product_id']);
                $item['product_name'] = $product ? $product->name : 'Bilinmeyen Ürün';
                $item['product_price'] = $product ? $product->price : 0;
                return $item;
            })->toArray();

            // İndirim kodu bilgisini ekle
            $order->has_discount = \App\Models\DiscountCode::where('basket_id', $order->id)
                ->where('is_used', 1)
                ->exists();
        }

        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $totalOrders,
            'total_pages' => ceil($totalOrders / $perPage),
            'showing' => min($page * $perPage, $totalOrders),
            'total_orders' => $totalOrders
        ];

        return view('orders', compact('orders', 'pagination', 'totalAmount', 'filter'));
    }
} 