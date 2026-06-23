<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->enum('plan_type', ['basic', 'pro', 'premium'])->default('basic')->after('status');
            $table->decimal('subscription_fee', 8, 2)->default(0)->after('plan_type');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_fee');
            $table->boolean('subscription_active')->default(false)->after('subscription_expires_at');
            $table->decimal('commission_rate', 5, 2)->default(10.00)->after('subscription_active');
            $table->unsignedBigInteger('visit_count')->default(0)->after('commission_rate');
            $table->timestamp('last_visited_at')->nullable()->after('visit_count');
            $table->unsignedInteger('products_approved')->default(0)->after('last_visited_at');
            $table->unsignedInteger('products_pending')->default(0)->after('products_approved');
            $table->text('admin_notes')->nullable()->after('products_pending');
            $table->timestamp('approved_at')->nullable()->after('admin_notes');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->timestamp('suspended_at')->nullable()->after('rejected_at');
            $table->string('rejection_reason')->nullable()->after('suspended_at');
            $table->string('suspension_reason')->nullable()->after('rejection_reason');
            $table->boolean('expiry_reminder_sent')->default(false)->after('suspension_reason');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'plan_type', 'subscription_fee', 'subscription_expires_at',
                'subscription_active', 'commission_rate', 'visit_count',
                'last_visited_at', 'products_approved', 'products_pending',
                'admin_notes', 'approved_at', 'rejected_at', 'suspended_at',
                'rejection_reason', 'suspension_reason', 'expiry_reminder_sent',
            ]);
        });
    }
};
