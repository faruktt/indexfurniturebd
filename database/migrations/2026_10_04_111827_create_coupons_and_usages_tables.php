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
        // 1. Coupons Table
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'fixed'])->default('fixed');
            $table->decimal('value', 10, 2);
            $table->decimal('min_spend', 10, 2)->nullable();
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable(); // Total times this coupon can be used across all users
            $table->integer('user_limit')->default(1);  // Max times a single user can use this coupon
            $table->integer('used_count')->default(0);  // Times used so far
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Coupon Usages Table (tracks which user / order used which coupon)
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->timestamps();
        });

        // 3. Add coupon_code column to orders table if not present
        if (!Schema::hasColumn('orders', 'coupon_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('coupon_code')->nullable()->after('discount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'coupon_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('coupon_code');
            });
        }
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
    }
};
