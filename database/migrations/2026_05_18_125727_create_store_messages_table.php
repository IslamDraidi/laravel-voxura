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
        Schema::create('store_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['pending', 'approved', 'flagged', 'rejected', 'replied'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->text('store_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('forwarded_at')->nullable();
            $table->boolean('auto_approved')->default(false);
            $table->json('filter_flags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_messages');
    }
};
