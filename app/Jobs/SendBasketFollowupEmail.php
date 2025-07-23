<?php

namespace App\Jobs;

use App\Models\Basket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\BasketDelivered;

class SendBasketFollowupEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    public function handle(): void
    {
        $basket = $this->basket;
        if ($basket->is_delivered == 1) return; // Zaten teslim edilmişse tekrar mail gönderme
        $basket->is_delivered = 1;
        $basket->save();
        try {
            \Mail::to($basket->email)->send(new \App\Mail\BasketDelivered($basket));
        } catch (\Exception $e) {
            \Log::error('BasketDelivered mail failed', ['error' => $e->getMessage(), 'basket_id' => $basket->id]);
        }
    }
} 