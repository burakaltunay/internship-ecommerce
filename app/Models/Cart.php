<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Include the Session facade

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
        'product_data'
    ];

    protected $casts = [
        'product_data' => 'array',
        'price' => 'decimal:2'
    ];

    /**
     * Defines the relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship with the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to filter the cart by the current authenticated user or session ID.
     */
    public function scopeForCurrentUser($query)
    {
        if (Auth::check()) {
            return $query->where('user_id', Auth::id());
        }

        // Handles guest users using session_id.
        $sessionId = Session::getId();
        return $query->where('session_id', $sessionId);
    }

    /**
     * Retrieves cart items for the current user or session.
     */
    public static function getCartItems()
    {
        return self::forCurrentUser()
            ->with('product')
            ->get();
    }

    /**
     * Retrieves the total quantity of items in the cart for the current user or session.
     */
    public static function getCartCount()
    {
        return self::forCurrentUser()->sum('quantity') ?: 0;
    }

    /**
     * Retrieves the total price of all items in the cart for the current user or session.
     */
    public static function getCartTotal()
    {
        return self::forCurrentUser()
            ->selectRaw('SUM(quantity * price) as total')
            ->value('total') ?: 0;
    }

    /**
     * Adds an item to the cart or updates its quantity if it already exists.
     *
     * @param int $productId
     * @param int $quantity
     * @param float $price
     * @param array $productData
     * @return Cart
     */
    public static function addOrUpdateItem(int $productId, int $quantity, float $price, array $productData): Cart
    {
        $cartItem = self::forCurrentUser()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Update quantity, price, and product data for existing item.
            $cartItem->quantity += $quantity;
            $cartItem->price = $price;
            $cartItem->product_data = $productData;
            $cartItem->save();
        } else {
            // Create a new cart item.
            $userId = Auth::id();
            $sessionId = Session::getId();

            $cartItem = self::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId, // Null if user is authenticated
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'product_data' => $productData
            ]);
        }

        return $cartItem;
    }

    /**
     * Updates the quantity of an item in the cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return Cart|null
     */
    public static function updateItemQuantity(int $productId, int $quantity): ?Cart
    {
        $cartItem = self::forCurrentUser()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            if ($quantity > 0) {
                $cartItem->quantity = $quantity;
                $cartItem->save();
                return $cartItem;
            } else {
                $cartItem->delete(); // Remove item if quantity is 0.
                return null;
            }
        }
        return null; // Item not found.
    }

    /**
     * Removes an item from the cart.
     *
     * @param int $productId
     * @return bool
     */
    public static function removeItem(int $productId): bool
    {
        return self::forCurrentUser()
            ->where('product_id', $productId)
            ->delete();
    }

    /**
     * Clears all items from the cart for the current user or session.
     */
    public static function clearCart()
    {
        return self::forCurrentUser()->delete();
    }

    /**
     * Merges a guest user's cart with an authenticated user's cart after login.
     *
     * @param string $oldSessionId - The old session ID of the guest user.
     * @param int $userId - The ID of the logged-in user.
     * @return bool
     */
    public static function mergeGuestCart(string $oldSessionId, int $userId): bool
    {
        $guestCartItems = self::where('session_id', $oldSessionId)
            ->whereNull('user_id')
            ->get();

        foreach ($guestCartItems as $guestItem) {
            $userCartItem = self::where('user_id', $userId)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($userCartItem) {
                // Increment quantity if item already exists in user's cart.
                $userCartItem->quantity += $guestItem->quantity;
                $userCartItem->save();
            } else {
                // Add new item to user's cart.
                self::create([
                    'user_id' => $userId,
                    'session_id' => null, // Set session_id to null as it's now tied to a user.
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                    'price' => $guestItem->price,
                    'product_data' => $guestItem->product_data
                ]);
            }

            // Delete the guest cart item.
            $guestItem->delete();
        }

        return true;
    }

    /**
     * Checks if a product exists in the cart for the current user or session.
     */
    public static function hasProduct(int $productId): bool
    {
        return self::forCurrentUser()
            ->where('product_id', $productId)
            ->exists();
    }

    /**
     * Retrieves the quantity of a specific product in the cart for the current user or session.
     */
    public static function getProductQuantity(int $productId): int
    {
        return self::forCurrentUser()
            ->where('product_id', $productId)
            ->value('quantity') ?: 0;
    }
}
