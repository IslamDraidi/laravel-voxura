<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('failure_reason')->nullable()->after('transaction_id');
            $table->string('failure_code')->nullable()->after('failure_reason');
            $table->unsignedInteger('attempts')->default(0)->after('failure_code');
            $table->timestamp('last_attempted_at')->nullable()->after('attempts');
            $table->string('gateway')->nullable()->after('last_attempted_at');
            $table->json('gateway_response')->nullable()->after('gateway');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'failure_reason', 'failure_code', 'attempts',
                'last_attempted_at', 'gateway', 'gateway_response',
            ]);
        });
    }
};
