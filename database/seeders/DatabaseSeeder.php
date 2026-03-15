<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        // User
 User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password',
]);

        // Categories
        $outerwear = Category::create([
            'name' => 'Outerwear',
            'slug' => 'outerwear',
        ]);

        $watches = Category::create([
            'name' => 'Watches',
            'slug' => 'watches',
        ]);

        $bags = Category::create([
            'name' => 'Bags',
            'slug' => 'bags',
        ]);

        $dresses = Category::create([
            'name' => 'Dresses',
            'slug' => 'dresses',
        ]);

        $footwear = Category::create([
            'name' => 'Footwear',
            'slug' => 'footwear',
        ]);

        $jewelry = Category::create([
            'name' => 'Jewelry',
            'slug' => 'jewelry',
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'slug' => 'accessories',
        ]);



        // Products
        Product::create([
            'name'        => 'Voxura Leather Jacket',
            'slug'        => 'voxura-leather-jacket',
            'description' => 'Premium genuine leather jacket with modern fit',
            'price'       => 599,
            'stock'       => 50,
            'image'       => 'jacket.jpg',
            'category_id' => $outerwear->id,
        ]);

        Product::create([
            'name'        => 'Voxura Luxury Watch',
            'slug'        => 'voxura-luxury-watch',
            'description' => 'Swiss-made luxury timepiece with sapphire crystal',
            'price'       => 899,
            'stock'       => 30,
            'image'       => 'watch.jpg',
            'category_id' => $watches->id,
        ]);

        Product::create([
            'name'        => 'Voxura Designer Handbag',
            'slug'        => 'voxura-designer-handbag',
            'description' => 'Handcrafted leather handbag with signature design',
            'price'       => 1299,
            'stock'       => 20,
            'image'       => 'handbag.jpg',
            'category_id' => $bags->id,
        ]);

         Product::create([
            'name'        => 'Voxura Premium Sneakers',
            'slug'        => 'voxura-premium-sneakers',
            'description' => 'Limited edition sneakers with comfort cushioning',
            'price'       => 249,
            'stock'       => 25,
            'image'       => 'sneakers.jpg',
            'category_id' => $footwear->id,
        ]);



         Product::create([
            'name'        => 'Voxura Gold Necklace',
            'slug'        => 'voxura-gold-necklace',
            'description' => '18k gold plated chain necklace',
            'price'       => 549,
            'stock'       => 15,
            'image'       => 'necklace.jpg',
            'category_id' => $jewelry->id,
        ]);


         Product::create([
            'name'        => 'Voxura Wool Coat',
            'slug'        => 'voxura-wool-coat',
            'description' => 'Double breasted wool coat with tailored fit',
            'price'       => 799,
            'stock'       => 35,
            'image'       => 'coat.jpg',
            'category_id' => $outerwear->id,
        ]);

         Product::create([
            'name'        => 'Voxura Designer Sunglasses',
            'slug'        => 'voxura-designer-sunglasses',
            'description' => 'Polarized sunglasses with UV protection',
            'price'       => 329,
            'stock'       => 39,
            'image'       => 'sunglasses.jpg',
            'category_id' => $accessories->id,
        ]);


         Product::create([
            'name'        => 'Voxura Silk Dress',
            'slug'        => 'voxura-silk-dress',
            'description' => 'Elegant silk dress with flowing silhouette',
            'price'       => 499,
            'stock'       => 25,
            'image'       => 'dress.jpg',
            'category_id' => $dresses->id,
        ]);
    }
}