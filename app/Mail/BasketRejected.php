<?php

namespace App\Mail;

use App\Models\Basket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BasketRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    public function build()
    {
        return $this->subject('Sepetiniz Onaylanmadı')
            ->view('emails.basket_rejected');
    }
} 