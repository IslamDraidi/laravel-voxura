<?php

namespace Tests\Feature\Components;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_products(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewHas('products');
        $this->assertCount(5, $response->viewData('products'));
    }

    public function test_index_filters_by_size(): void
    {
        $medium = Product::factory()->create();
        $large  = Product::factory()->create();

        ProductVariant::create(['product_id' => $medium->id, 'type' => 'Size', 'value' => 'M']);
        ProductVariant::create(['product_id' => $large->id,  'type' => 'Size', 'value' => 'XL']);

        $response = $this->get(route('products.index') . '?size[]=M');

        $products = $response->viewData('products');
        $this->assertCount(1, $products);
        $this->assertEquals($medium->id, $products->first()->id);
    }

    public function test_index_filters_by_price_range(): void
    {
        Product::factory()->create(['price' => 30.00]);
        Product::factory()->create(['price' => 200.00]);

        $response = $this->get(route('products.index') . '?price_range=under_50');

        $products = $response->viewData('products');
        $this->assertCount(1, $products);
        $this->assertTrue((float) $products->first()->price <= 50);
    }

    public function test_index_sorted_by_price_asc(): void
    {
        Product::factory()->create(['price' => 300.00]);
        Product::factory()->create(['price' => 50.00]);
        Product::factory()->create(['price' => 150.00]);

        $response = $this->get(route('products.index') . '?sort=price_asc');

        $products = $response->viewData('products');
        $prices   = $products->pluck('price')->map(fn ($p) => (float) $p)->values();

        $this->assertEquals($prices->sort()->values()->toArray(), $prices->toArray());
    }

    public function test_show_stores_recently_viewed_in_session(): void
    {
        $product = Product::factory()->create();

        $this->get(route('products.show', $product));

        $this->assertContains($product->id, session('recently_viewed'));
    }

    public function test_show_passes_recently_viewed_to_view(): void
    {
        $p1 = Product::factory()->create();
        $p2 = Product::factory()->create();

        $this->withSession(['recently_viewed' => [$p1->id]])
             ->get(route('products.show', $p2));

        // after viewing p2, session has [p2->id, p1->id]
        $this->assertContains($p1->id, session('recently_viewed'));
        $this->assertContains($p2->id, session('recently_viewed'));
    }
}
