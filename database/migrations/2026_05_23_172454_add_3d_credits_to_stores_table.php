<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->unsignedInteger('credits_balance')
                  ->default(0)
                  ->after('commission_rate');

            $table->unsignedInteger('credits_granted_total')
                  ->default(0)
                  ->after('credits_balance');

            $table->unsignedInteger('credits_used_total')
                  ->default(0)
                  ->after('credits_granted_total');

            $table->unsignedInteger('credits_bonus')
                  ->default(0)
                  ->after('credits_used_total');

            $table->timestamp('credits_last_topped_up_at')
                  ->nullable()
                  ->after('credits_bonus');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'credits_balance',
                'credits_granted_total',
                'credits_used_total',
                'credits_bonus',
                'credits_last_topped_up_at',
            ]);
        });
    }
};
