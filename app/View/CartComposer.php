<?php

namespace App\View\Composers;

use App\Services\CartService;
use Illuminate\View\View;

class CartComposer
{
    /**
     * Sepet bilgilerini tüm view'lara gönder
     */
    public function compose(View $view)
    {
        $cartData = CartService::getCartDataForView();

        $view->with([
            'cart_items' => $cartData['items'],
            'cart_count' => $cartData['count'],
            'cart_total' => $cartData['total'],
            'cart_formatted_total' => $cartData['formatted_total']
        ]);
    }
}
