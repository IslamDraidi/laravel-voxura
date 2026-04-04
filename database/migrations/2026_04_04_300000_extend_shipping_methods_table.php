<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->enum('type', ['flat', 'per_unit', 'weight_based', 'free', 'custom'])->default('flat')->after('name_translations');
            $table->decimal('base_rate', 10, 2)->default(0)->after('type');
            $table->decimal('per_unit_rate', 10, 2)->nullable()->after('base_rate');
            $table->decimal('weight_rate', 10, 4)->nullable()->after('per_unit_rate');
            $table->enum('weight_unit', ['kg', 'lb'])->default('kg')->after('weight_rate');
            $table->decimal('free_above', 10, 2)->nullable()->after('weight_unit');
            $table->decimal('min_order_amount', 10, 2)->nullable()->after('free_above');
            $table->decimal('max_order_amount', 10, 2)->nullable()->after('min_order_amount');
            $table->decimal('max_weight', 10, 4)->nullable()->after('max_order_amount');
            $table->string('channel')->nullable()->after('max_weight');
            $table->integer('estimated_days_min')->nullable()->after('channel');
            $table->integer('estimated_days_max')->nullable()->after('estimated_days_min');
            $table->integer('sort_order')->default(0)->after('estimated_days_max');
            $table->json('metadata')->nullable()->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn([
                'name_translations', 'type', 'base_rate', 'per_unit_rate',
                'weight_rate', 'weight_unit', 'free_above', 'min_order_amount',
                'max_order_amount', 'max_weight', 'channel', 'estimated_days_min',
                'estimated_days_max', 'sort_order', 'metadata',
            ]);
        });
    }
};
