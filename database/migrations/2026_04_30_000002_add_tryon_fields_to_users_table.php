<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_body_model')->default(false)->after('role');
            $table->string('body_model_path')->nullable()->after('has_body_model');
            $table->timestamp('body_model_generated_at')->nullable()->after('body_model_path');
            $table->unsignedSmallInteger('body_height_cm')->nullable()->after('body_model_generated_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'has_body_model',
                'body_model_path',
                'body_model_generated_at',
                'body_height_cm',
            ]);
        });
    }
};
