<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->json('items');
            $table->decimal('total_price', 10, 2);
            $table->string('email');
            $table->tinyInteger('is_draft')->default(1); // 1: draft, 0: finalized
            $table->tinyInteger('is_approved')->default(0); // 1: approved, 0: not approved
            $table->tinyInteger('is_delivered')->default(0); // 1: delivered, 0: not delivered
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baskets');
    }
}; 