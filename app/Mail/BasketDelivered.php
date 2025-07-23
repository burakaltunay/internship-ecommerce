<?php

namespace App\Mail;

use App\Models\Basket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BasketDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    public function build()
    {
        return $this->subject('Sepetiniz Teslim Edildi')
            ->view('emails.basket_delivered');
    }
} 