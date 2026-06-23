<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('model3d_requested_by_store')
                  ->nullable()
                  ->after('model3d_status')
                  ->constrained('stores')
                  ->nullOnDelete();

            $table->timestamp('model3d_requested_at')
                  ->nullable()
                  ->after('model3d_requested_by_store');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['model3d_requested_by_store']);
            $table->dropColumn(['model3d_requested_by_store', 'model3d_requested_at']);
        });
    }
};
