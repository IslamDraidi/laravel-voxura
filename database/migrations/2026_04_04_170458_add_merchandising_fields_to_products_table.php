<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sale_badge')->nullable()->after('image');
            $table->boolean('is_new')->default(false)->after('sale_badge');
            $table->unsignedInteger('max_order_quantity')->default(5)->after('is_new');
            $table->unsignedInteger('stock_alert_threshold')->default(6)->after('max_order_quantity');
            $table->string('delivery_estimate')->nullable()->after('stock_alert_threshold');
            $table->string('material')->nullable()->after('delivery_estimate');
            $table->string('fit')->nullable()->after('material');
            $table->text('care_instructions')->nullable()->after('fit');
            $table->string('sku')->nullable()->after('care_instructions');
            $table->text('shipping_returns')->nullable()->after('sku');
            $table->json('color_swatches')->nullable()->after('shipping_returns');
            $table->json('size_guide')->nullable()->after('color_swatches');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sale_badge',
                'is_new',
                'max_order_quantity',
                'stock_alert_threshold',
                'delivery_estimate',
                'material',
                'fit',
                'care_instructions',
                'sku',
                'shipping_returns',
                'color_swatches',
                'size_guide',
            ]);
        });
    }
};
