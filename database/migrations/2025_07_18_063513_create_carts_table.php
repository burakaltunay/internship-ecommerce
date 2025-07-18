<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign key for authenticated users.
            $table->string('session_id')->nullable(); // Used for guest users.
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('price', 8, 2); // Price of the product at the time of addition.
            $table->json('product_data')->nullable(); // Snapshot data like product name, image, etc.
            $table->timestamps();

            // Adds indexes for faster lookups.
            $table->index('user_id');
            $table->index('session_id');
            $table->index('product_id');

            // Unique constraints were removed or simplified.
            // A single composite unique constraint might be safer,
            // but NULL value behavior for UNIQUE varies by database.
            // The most robust approach is to manage uniqueness at the application layer
            // (e.g., in CartController/Cart Model, which already uses 'firstOrNew').
            // Therefore, removing the dual unique constraint here.
            // If desired, a single composite index could be kept:
            // $table->unique(['user_id', 'session_id', 'product_id']);
            // However, this can still cause issues if both are NULL or both are populated.
            // For now, assuming application-level control is sufficient, these are removed.
        });

        // Foreign key constraints can be added in a separate addColumn or after table definition
        // to prevent migration errors if 'products' or 'users' tables do not yet exist.
        // ALTERNATIVELY, use foreignId() which automatically creates index and foreign key.
        Schema::table('carts', function (Blueprint $table) {
            // If user_id and product_id were not defined as foreignId(), add them manually:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverses the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
