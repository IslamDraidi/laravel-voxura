<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // User
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ],
        );

        User::updateOrCreate(
            ['email' => 'islamdraidi@gmail.com'],
            [
                'name' => 'Islam Draidi',
                'password' => '123123123',
                'role' => 'admin',
                'is_blocked' => false,
            ],
        );

        // Categories
        $outerwear = Category::firstOrCreate([
            'name' => 'Outerwear',
            'slug' => 'outerwear',
        ]);

        $watches = Category::firstOrCreate([
            'name' => 'Watches',
            'slug' => 'watches',
        ]);

        $bags = Category::firstOrCreate([
            'name' => 'Bags',
            'slug' => 'bags',
        ]);

        $dresses = Category::firstOrCreate([
            'name' => 'Dresses',
            'slug' => 'dresses',
        ]);

        $footwear = Category::firstOrCreate([
            'name' => 'Footwear',
            'slug' => 'footwear',
        ]);

        $jewelry = Category::firstOrCreate([
            'name' => 'Jewelry',
            'slug' => 'jewelry',
        ]);

        $accessories = Category::firstOrCreate([
            'name' => 'Accessories',
            'slug' => 'accessories',
        ]);

        // Products
        Product::updateOrCreate([
            'slug' => 'voxura-leather-jacket',
        ], [
            'name' => 'Voxura Leather Jacket',
            'description' => 'Premium genuine leather jacket with modern fit',
            'price' => 599,
            'stock' => 50,
            'image' => 'jacket.jpg',
            'category_id' => $outerwear->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-luxury-watch',
        ], [
            'name' => 'Voxura Luxury Watch',
            'description' => 'Swiss-made luxury timepiece with sapphire crystal',
            'price' => 899,
            'stock' => 30,
            'image' => 'watch.jpg',
            'category_id' => $watches->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-designer-handbag',
        ], [
            'name' => 'Voxura Designer Handbag',
            'description' => 'Handcrafted leather handbag with signature design',
            'price' => 1299,
            'stock' => 20,
            'image' => 'handbag.jpg',
            'category_id' => $bags->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-premium-sneakers',
        ], [
            'name' => 'Voxura Premium Sneakers',
            'description' => 'Limited edition sneakers with comfort cushioning',
            'price' => 249,
            'stock' => 25,
            'image' => 'sneakers.jpg',
            'category_id' => $footwear->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-gold-necklace',
        ], [
            'name' => 'Voxura Gold Necklace',
            'description' => '18k gold plated chain necklace',
            'price' => 549,
            'stock' => 15,
            'image' => 'necklace.jpg',
            'category_id' => $jewelry->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-wool-coat',
        ], [
            'name' => 'Voxura Wool Coat',
            'description' => 'Double breasted wool coat with tailored fit',
            'price' => 799,
            'stock' => 35,
            'image' => 'coat.jpg',
            'category_id' => $outerwear->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-designer-sunglasses',
        ], [
            'name' => 'Voxura Designer Sunglasses',
            'description' => 'Polarized sunglasses with UV protection',
            'price' => 329,
            'stock' => 39,
            'image' => 'sunglasses.jpg',
            'category_id' => $accessories->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-silk-dress',
        ], [
            'name' => 'Voxura Silk Dress',
            'description' => 'Elegant silk dress with flowing silhouette',
            'price' => 499,
            'stock' => 25,
            'image' => 'dress.jpg',
            'category_id' => $dresses->id,
        ]);
    }
}
