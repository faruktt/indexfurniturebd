<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Shipping charges
        Schema::create('shipping_charges', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g. Home Delivery (Inside Dhaka)
            $table->string('key')->unique(); // inside_dhaka, outside_dhaka, pickup
            $table->decimal('charge', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('customer_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('country')->default('Bangladesh');
            $table->string('district');
            $table->string('area');
            $table->string('postal_code')->nullable();
            $table->text('address');
            $table->string('delivery_method'); // title of delivery method
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->string('payment_method')->default('sslcommerz'); // sslcommerz, cod, etc.
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('grand_total', 12, 2);
            $table->string('order_status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Order items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('product_image')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('shipping_charges');
    }
};
