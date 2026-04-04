<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('shipping_zone_id')->nullable()->constrained()->nullOnDelete()->after('shipping_method_id');
            $table->json('tax_breakdown')->nullable()->after('tax_amount');
            $table->decimal('shipping_tax_amount', 10, 2)->default(0)->after('tax_breakdown');
            $table->decimal('subtotal', 10, 2)->default(0)->after('shipping_tax_amount');
            $table->decimal('grand_total', 10, 2)->default(0)->after('subtotal');
            $table->string('currency', 3)->default('USD')->after('grand_total');
            $table->string('channel')->nullable()->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_zone_id']);
            $table->dropColumn([
                'shipping_zone_id', 'tax_breakdown', 'shipping_tax_amount',
                'subtotal', 'grand_total', 'currency', 'channel',
            ]);
        });
    }
};
