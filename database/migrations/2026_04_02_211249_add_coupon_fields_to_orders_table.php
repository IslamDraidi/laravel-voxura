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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'coupon_id')) {
                $table->foreignId('coupon_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            } else {
                $table->foreign('coupon_id')->references('id')->on('coupons')->nullOnDelete();
            }
            $table->decimal('discount_amount', 10, 2)->default(0)->after('total_amount');
            $table->string('coupon_code')->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'discount_amount', 'coupon_code']);
        });
    }
};
