<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method'); // pix, credit_card, boleto
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('order_status')->default('pending'); // pending, processing, completed, cancelled
            $table->string('payment_id')->nullable(); // Payment gateway reference ID
            // Dados do cartão de crédito (criptografados)
            $table->string('card_holder_name')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_expiry')->nullable();
            $table->string('card_cvv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
