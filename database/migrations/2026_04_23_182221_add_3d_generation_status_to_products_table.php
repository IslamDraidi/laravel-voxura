<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('model3d_status', ['idle', 'queued', 'processing', 'ready', 'failed'])
                ->default('idle')
                ->after('has_3d_model');
            $table->timestamp('model3d_queued_at')->nullable()->after('model3d_status');
            $table->timestamp('model3d_generated_at')->nullable()->after('model3d_queued_at');
            $table->text('model3d_error')->nullable()->after('model3d_generated_at');
            $table->string('model3d_selected_image')->nullable()->after('model3d_error');
            $table->string('model3d_job_id')->nullable()->after('model3d_selected_image');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'model3d_status',
                'model3d_queued_at',
                'model3d_generated_at',
                'model3d_error',
                'model3d_selected_image',
                'model3d_job_id',
            ]);
        });
    }
};