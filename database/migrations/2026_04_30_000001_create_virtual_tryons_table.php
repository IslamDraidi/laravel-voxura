<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('virtual_tryons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('photo_path')->nullable();
            $table->string('body_model_path')->nullable();
            $table->string('result_model_path')->nullable();

            $table->enum('status', [
                'pending',
                'processing_body',
                'processing_fit',
                'ready',
                'failed',
            ])->default('pending');

            $table->unsignedSmallInteger('height_cm')->nullable();
            $table->text('error_message')->nullable();
            $table->boolean('photo_consent')->default(false);

            $table->timestamp('queued_at')->nullable();
            $table->timestamp('body_generated_at')->nullable();
            $table->timestamp('result_generated_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_tryons');
    }
};
