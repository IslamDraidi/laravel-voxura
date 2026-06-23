<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->enum('onboarding_status', [
                'draft',
                'plan_selected',
                'paid',
                'ready',
                'live',
            ])->default('draft')->after('name');

            $table->string('business_name')->nullable()->after('onboarding_status');
            $table->string('business_id')->nullable()->after('business_name');
            $table->string('business_phone')->nullable()->after('business_id');

            $table->string('billing_cycle')->default('monthly')->after('plan_type');

            $table->decimal('monthly_fee', 8, 2)->nullable()->after('subscription_fee');
            $table->decimal('yearly_fee', 8, 2)->nullable()->after('monthly_fee');

            $table->string('payment_reference')->nullable()->after('expiry_reminder_sent');
            $table->string('payment_method')->nullable()->after('payment_reference');
            $table->boolean('bank_transfer_pending')->default(false)->after('payment_method');

            $table->timestamp('last_payment_at')->nullable()->after('bank_transfer_pending');
            $table->timestamp('subscription_start')->nullable()->after('last_payment_at');

            $table->timestamp('published_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'onboarding_status',
                'business_name',
                'business_id',
                'business_phone',
                'billing_cycle',
                'monthly_fee',
                'yearly_fee',
                'payment_reference',
                'payment_method',
                'bank_transfer_pending',
                'last_payment_at',
                'subscription_start',
                'published_at',
            ]);
        });
    }
};
