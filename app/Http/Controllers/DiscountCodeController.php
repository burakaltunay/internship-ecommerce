<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountCode;

class DiscountCodeController extends Controller
{
    public function validateCode(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string',
                'email' => 'required|email',
                'total' => 'required|numeric|min:0',
            ]);

            $code = $request->code;
            $email = $request->email;
            $total = floatval($request->total);

            \Log::info('validateCode: params', compact('code', 'email', 'total'));

            $discount = DiscountCode::where('code', $code)
                ->where('email', $email)
                ->where('is_used', 0)
                ->where(function($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->first();

            if (!$discount) {
                \Log::info('validateCode: code not found or not usable', compact('code', 'email'));
                return response()->json([
                    'success' => false,
                    'message' => 'İndirim kodu geçersiz, kullanılmış veya süresi dolmuş.'
                ], 200);
            }

            // İndirim kodu kullanıldığında basket_id atanır ama is_used = 0 kalır
            // is_used değeri sepet onaylandığında 1 yapılacak
            $discount->basket_id = $request->basket_id ?? null;
            $discount->save();
            
            \Log::info('İndirim kodu kullanıldı, basket_id atandı', [
                'discount_id' => $discount->id,
                'basket_id' => $discount->basket_id,
                'code' => $discount->code,
                'is_used' => $discount->is_used
            ]);

            $discountedTotal = round($total * 0.8, 2);
            \Log::info('validateCode: code used successfully', ['discount_id' => $discount->id, 'discounted_total' => $discountedTotal]);

            return response()->json([
                'success' => true,
                'discounted_total' => $discountedTotal,
                'message' => 'İndirim kodu başarıyla uygulandı.'
            ]);
        } catch (\Exception $e) {
            \Log::error('DiscountCode validateCode error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Sunucu hatası. Lütfen tekrar deneyin.'
            ], 500);
        }
    }
}
