<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tax_rates', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->enum('type', ['percentage', 'fixed', 'compound'])->default('percentage')->after('name_translations');
            $table->enum('scope', ['product', 'category', 'order', 'shipping'])->default('product')->after('type');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete()->after('scope');
            $table->string('channel')->nullable()->after('category_id');
            $table->string('country', 2)->nullable()->after('channel');
            $table->string('region')->nullable()->after('country');
            $table->string('postal_code_pattern')->nullable()->after('region');
            $table->integer('priority')->default(0)->after('postal_code_pattern');
            $table->boolean('is_inclusive')->default(false)->after('priority');
            $table->boolean('apply_to_shipping')->default(false)->after('is_inclusive');
            $table->date('valid_from')->nullable()->after('apply_to_shipping');
            $table->date('valid_to')->nullable()->after('valid_from');
            $table->json('metadata')->nullable()->after('valid_to');
        });
    }

    public function down(): void
    {
        Schema::table('tax_rates', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'name_translations', 'type', 'scope', 'category_id', 'channel',
                'country', 'region', 'postal_code_pattern', 'priority',
                'is_inclusive', 'apply_to_shipping', 'valid_from', 'valid_to', 'metadata',
            ]);
        });
    }
};
