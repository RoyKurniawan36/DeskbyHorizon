<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'approved', 'paid', 'shipped', 'delivered'])->default('pending');
            $table->text('shipping_address');
            $table->enum('payment_method', ['cash_on_delivery', 'bank_transfer'])->default('cash_on_delivery');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};