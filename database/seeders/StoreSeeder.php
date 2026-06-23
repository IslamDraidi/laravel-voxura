<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $admin = \App\Models\User::where('role', 'admin')->first();
        $ownerId = $admin?->id ?? 1;

        $luna = Store::firstOrCreate(
            ['slug' => 'luna-fashion'],
            [
                'name'            => 'Luna Fashion',
                'tagline'         => 'Explore new arrivals with 3D product views.',
                'description'     => 'Luna Fashion brings you the finest women\'s clothing with modern cuts and timeless style. Shop dresses, jackets, and accessories.',
                'banner_path'     => 'images/stores/luna-banner.jpg',
                'logo_path'       => null,
                'is_featured'     => true,
                'status'          => 'approved',
                'has_3d_products' => false,
                'category_tags'   => ['Dresses', 'Jackets', 'Accessories'],
                'owner_id'        => $ownerId,
            ]
        );

        $nova = Store::firstOrCreate(
            ['slug' => 'nova-threads'],
            [
                'name'            => 'Nova Threads',
                'tagline'         => 'Contemporary men\'s fashion for every occasion.',
                'description'     => 'Nova Threads curates premium menswear — suits, casual shirts, and lifestyle accessories.',
                'banner_path'     => 'images/stores/nova-banner.jpg',
                'logo_path'       => null,
                'is_featured'     => false,
                'status'          => 'approved',
                'has_3d_products' => false,
                'category_tags'   => ['Suits', 'Casual', 'Accessories'],
                'owner_id'        => $ownerId,
            ]
        );

        $sole = Store::firstOrCreate(
            ['slug' => 'sole-society'],
            [
                'name'            => 'Sole Society',
                'tagline'         => 'Premium footwear you can feel in every step.',
                'description'     => 'Sole Society specialises in premium sneakers, boots, and designer footwear for men and women.',
                'banner_path'     => 'images/stores/sole-banner.jpg',
                'logo_path'       => null,
                'is_featured'     => false,
                'status'          => 'approved',
                'has_3d_products' => false,
                'category_tags'   => ['Sneakers', 'Boots', 'Formal'],
                'owner_id'        => $ownerId,
            ]
        );

        Product::where('slug', 'voxura-silk-dress')->update(['store_id' => $luna->id]);
        Product::where('slug', 'voxura-wool-coat')->update(['store_id' => $luna->id]);
        Product::where('slug', 'voxura-gold-necklace')->update(['store_id' => $luna->id]);
        Product::where('slug', 'voxura-designer-handbag')->update(['store_id' => $luna->id]);
        Product::where('slug', 'voxura-leather-jacket')->update(['store_id' => $nova->id]);
        Product::where('slug', 'voxura-luxury-watch')->update(['store_id' => $nova->id]);
        Product::where('slug', 'voxura-designer-sunglasses')->update(['store_id' => $nova->id]);
        Product::where('slug', 'voxura-premium-sneakers')->update(['store_id' => $sole->id]);
    }
}
