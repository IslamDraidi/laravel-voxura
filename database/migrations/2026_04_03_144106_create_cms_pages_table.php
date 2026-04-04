<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->enum('status', ['active', 'draft'])->default('draft');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default pages
        DB::table('cms_pages')->insert([
            ['title' => 'Home',           'slug' => 'home',           'content' => '<h1>Welcome to Voxura</h1>', 'status' => 'active',  'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'About Us',       'slug' => 'about-us',       'content' => '<h1>About Us</h1>',         'status' => 'active',  'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Contact',        'slug' => 'contact',        'content' => '<h1>Contact Us</h1>',       'status' => 'active',  'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => '<h1>Privacy Policy</h1>',  'status' => 'active',  'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Terms of Service', 'slug' => 'terms',          'content' => '<h1>Terms of Service</h1>', 'status' => 'active',  'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'FAQ',            'slug' => 'faq',            'content' => '<h1>FAQ</h1>',              'status' => 'draft',   'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
