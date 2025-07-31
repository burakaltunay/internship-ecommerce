<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Kullanıcı giriş yaptığında misafir sepetini kullanıcı sepetine taşır
     */
    public static function mergeGuestCartOnLogin($userId)
    {
        $sessionId = session()->getId();

        // Misafir sepetini kullanıcı sepetine birleştir
        Cart::mergeGuestCart($sessionId, $userId);

        return true;
    }

    /**
     * Sepet bilgilerini view'lar için hazırla
     */
    public static function getCartDataForView()
    {
        $cartItems = Cart::getCartItems();
        $cartCount = Cart::getCartCount();
        $cartTotal = Cart::getCartTotal();

        return [
            'items' => $cartItems,
            'count' => $cartCount,
            'total' => $cartTotal,
            'formatted_total' => number_format($cartTotal, 2, ',', '.') . ' ₺'
        ];
    }

    /**
     * Ürün sepete eklenebilir mi kontrol et
     */
    public static function canAddToCart($productId, $quantity = 1)
    {
        $product = Product::find($productId);

        if (!$product) {
            return [
                'can_add' => false,
                'message' => 'Ürün bulunamadı'
            ];
        }

        // Stok kontrolü
        if (isset($product->stock) && $product->stock < $quantity) {
            return [
                'can_add' => false,
                'message' => 'Yeterli stok bulunmamaktadır'
            ];
        }

        // Mevcut sepetteki miktar kontrolü
        $existingCartItem = Cart::where('product_id', $productId)
            ->forCurrentUser()
            ->first();

        if ($existingCartItem) {
            $totalQuantity = $existingCartItem->quantity + $quantity;

            if (isset($product->stock) && $product->stock < $totalQuantity) {
                return [
                    'can_add' => false,
                    'message' => 'Bu miktar sepete eklenemez. Stok yetersiz.'
                ];
            }
        }

        return [
            'can_add' => true,
            'message' => 'Ürün sepete eklenebilir'
        ];
    }

    /**
     * Sepeti temizle (sadece eski kayıtlar)
     */
    public static function clearOldCarts($days = 30)
    {
        $cutoffDate = now()->subDays($days);

        // Kullanıcısı olmayan ve belirli günden eski sepetleri temizle
        Cart::where('user_id', null)
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        return true;
    }

    /**
     * Sepet istatistikleri
     */
    public static function getCartStats()
    {
        $totalCarts = Cart::distinct('user_id', 'session_id')->count();
        $userCarts = Cart::whereNotNull('user_id')->distinct('user_id')->count();
        $guestCarts = Cart::whereNull('user_id')->distinct('session_id')->count();
        $totalItems = Cart::sum('quantity');
        $totalValue = Cart::selectRaw('SUM(quantity * price) as total')->value('total');

        return [
            'total_carts' => $totalCarts,
            'user_carts' => $userCarts,
            'guest_carts' => $guestCarts,
            'total_items' => $totalItems,
            'total_value' => $totalValue,
            'average_cart_value' => $totalCarts > 0 ? $totalValue / $totalCarts : 0
        ];
    }
}
