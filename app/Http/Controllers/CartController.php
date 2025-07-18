<?php

namespace App\Http\Controllers;

use App\Models\Cart; // Using the updated Cart model
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Lists items in the cart.
     * Uses static methods getCartItems, getCartCount, getCartTotal from the Cart model.
     */
    public function index()
    {
        try {
            $cartItems = Cart::getCartItems();
            $cartCount = Cart::getCartCount();
            $cartTotal = Cart::getCartTotal();

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cartItems,
                    'count' => $cartCount,
                    'total' => $cartTotal
                ]
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging.
            \Log::error('Error retrieving cart information: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving cart information. Please try again later.'
            ], 500);
        }
    }

    /**
     * Adds a product to the cart or updates its quantity.
     * Uses the addOrUpdateItem static method from the Cart model.
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1|max:99' // Total quantity from frontend
            ]);

            $productId = $request->product_id;
            $quantity = $request->quantity;

            // Retrieve product information.
            $product = Product::findOrFail($productId);

            // Stock check (if Product model has a stock field).
            // This check can also be moved to the Cart model's addOrUpdateItem method.
            if (isset($product->stock) && $product->stock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available.'
                ], 400);
            }

            // Prepare product data to be saved as JSON.
            $productData = [
                'name' => $product->name,
                'image' => $product->image ?? null,
                'slug' => $product->slug ?? null,
                'price' => $product->price // Price can be added to product_data, but a separate 'price' column also exists.
            ];

            // Add or update the item in the cart using the Cart model's static method.
            $cartItem = Cart::addOrUpdateItem($productId, $quantity, $product->price, $productData);

            // Return updated cart information.
            $cartCount = Cart::getCartCount();
            $cartTotal = Cart::getCartTotal();

            return response()->json([
                'success' => true,
                'message' => 'Product added/updated in cart.',
                'data' => [
                    'item' => $cartItem->fresh()->load('product'), // Load the product relationship
                    'cart_count' => $cartCount,
                    'cart_total' => $cartTotal
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data input.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }
        catch (\Exception $e) {
            \Log::error('Error adding product to cart: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the product to the cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Updates the quantity of an item in the cart.
     * Uses the updateItemQuantity static method from the Cart model.
     */
    public function update(Request $request, $productId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:0|max:99' // If quantity is 0, item will be removed
            ]);

            $quantity = $request->quantity;

            // Update the item quantity using the Cart model's static method.
            $cartItem = Cart::updateItemQuantity($productId, $quantity);

            if (is_null($cartItem)) {
                // If quantity was set to 0 and deleted, or product not found.
                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from cart or not found.',
                    'data' => [
                        'cart_count' => Cart::getCartCount(),
                        'cart_total' => Cart::getCartTotal()
                    ]
                ], 200); // 200 OK, as the operation was successful (removal or not found)
            }

            // Stock check (if Product model has a stock field).
            // This check can also be moved to the Cart model's updateItemQuantity method.
            if (isset($cartItem->product->stock) && $cartItem->product->stock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available.'
                ], 400);
            }

            // Return updated cart information.
            $cartCount = Cart::getCartCount();
            $cartTotal = Cart::getCartTotal();

            return response()->json([
                'success' => true,
                'message' => 'Product quantity updated.',
                'data' => [
                    'item' => $cartItem->fresh()->load('product'),
                    'cart_count' => $cartCount,
                    'cart_total' => $cartTotal
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data input.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating product quantity: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating product quantity. Please try again.'
            ], 500);
        }
    }

    /**
     * Removes a product from the cart.
     * Uses the removeItem static method from the Cart model.
     */
    public function remove($productId)
    {
        try {
            $deleted = Cart::removeItem($productId);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in your cart or could not be deleted.'
                ], 404);
            }

            // Return updated cart information.
            $cartCount = Cart::getCartCount();
            $cartTotal = Cart::getCartTotal();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart.',
                'data' => [
                    'cart_count' => $cartCount,
                    'cart_total' => $cartTotal
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error removing product from cart: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the product from the cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Clears the entire cart.
     * Uses the clearCart static method from the Cart model.
     */
    public function clear()
    {
        try {
            Cart::clearCart();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared.',
                'data' => [
                    'cart_count' => 0,
                    'cart_total' => 0
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error clearing cart: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while clearing the cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Processes the cart checkout.
     * This method will contain the order creation logic.
     */
    public function checkout(Request $request)
    {
        try {
            // User must be logged in.
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You need to log in to proceed with checkout.'
                ], 401);
            }

            $cartItems = Cart::getCartItems();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.'
                ], 400);
            }

            // Stock check for all items in the cart.
            foreach ($cartItems as $item) {
                // Use $item->product->name instead of $item->product_name.
                // Ensure product relationship is loaded (e.g., with('product') in getCartItems).
                if (isset($item->product->stock) && $item->product->stock < $item->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => ($item->product->name ?? 'Unknown Product') . ' has insufficient stock.'
                    ], 400);
                }
            }

            // Order creation code will be added here.
            // Typically, an Order model is created, OrderItems are saved, and stock is updated.
            // It's better to move this logic to a 'placeOrder' method within a CartService.
            // Example: $order = $this->cartService->placeOrder(Auth::id(), $cartItems);

            // Using transactions is crucial for critical operations like order creation.
            DB::beginTransaction();
            try {
                // Example order creation (replace with your actual Order model)
                // $order = Order::create([
                //     'user_id' => Auth::id(),
                //     'total_amount' => Cart::getCartTotal(),
                //     'status' => 'pending',
                // ]);

                // foreach ($cartItems as $item) {
                //     OrderItem::create([
                //         'order_id' => $order->id,
                //         'product_id' => $item->product_id,
                //         'quantity' => $item->quantity,
                //         'price' => $item->price, // Price at the time of order
                //     ]);
                //     // Update stock (if your Product model has a stock field)
                //     $item->product->decrement('stock', $item->quantity);
                // }

                // Clear the cart after successful checkout.
                Cart::clearCart();

                DB::commit(); // Commit the transaction.

                return response()->json([
                    'success' => true,
                    'message' => 'Your order has been successfully placed!',
                    'data' => [
                        'order_total' => Cart::getCartTotal(), // Will be 0 after clearing
                        'cart_count' => 0,
                        'cart_total' => 0
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack(); // Rollback transactions if an error occurs.
                throw $e; // Re-throw the exception.
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid order data.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating order: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the order. Please try again.'
            ], 500);
        }
    }

    /**
     * WEB: Displays the cart page (not an API route, returns a Blade view).
     */
    public function show()
    {
        $cartItems = Cart::getCartItems();
        $cartCount = Cart::getCartCount();
        $cartTotal = Cart::getCartTotal();

        return view('cart.show', compact('cartItems', 'cartCount', 'cartTotal'));
    }
}
