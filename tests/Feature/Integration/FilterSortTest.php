<?php

namespace Tests\Feature\Integration;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterSortTest extends TestCase
{
    use RefreshDatabase;

    public function test_multiple_filters_stack_correctly(): void
    {
        $medium = Product::factory()->create();
        ProductVariant::create(['product_id' => $medium->id, 'type' => 'Size',  'value' => 'M']);
        ProductVariant::create(['product_id' => $medium->id, 'type' => 'Color', 'value' => 'Black']);

        $large = Product::factory()->create();
        ProductVariant::create(['product_id' => $large->id, 'type' => 'Size',  'value' => 'XL']);
        ProductVariant::create(['product_id' => $large->id, 'type' => 'Color', 'value' => 'Black']);

        $response = $this->get(route('products.index') . '?size[]=M&color[]=Black');

        $products = $response->viewData('products');
        $this->assertCount(1, $products);
        $this->assertEquals($medium->id, $products->first()->id);
    }

    public function test_sort_by_price_ascending(): void
    {
        Product::factory()->create(['price' => 300]);
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 200]);

        $response = $this->get(route('products.index') . '?sort=price_asc');
        $prices   = $response->viewData('products')->pluck('price')->map(fn ($p) => (float) $p)->values();

        $this->assertEquals($prices->sort()->values()->toArray(), $prices->toArray());
    }

    public function test_sort_by_price_descending(): void
    {
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 300]);
        Product::factory()->create(['price' => 200]);

        $response = $this->get(route('products.index') . '?sort=price_desc');
        $products = $response->viewData('products');

        $this->assertTrue(
            (float) $products->first()->price >= (float) $products->last()->price
        );
    }

    public function test_price_range_filter_under_50(): void
    {
        Product::factory()->create(['price' => 30]);
        Product::factory()->create(['price' => 200]);

        $response = $this->get(route('products.index') . '?price_range=under_50');
        $products = $response->viewData('products');

        $this->assertCount(1, $products);
        $this->assertTrue((float) $products->first()->price <= 50);
    }

    public function test_price_range_filter_50_to_150(): void
    {
        Product::factory()->create(['price' => 40]);
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 200]);

        $response = $this->get(route('products.index') . '?price_range=50_150');
        $products = $response->viewData('products');

        $this->assertCount(1, $products);
        $this->assertEquals(100.0, (float) $products->first()->price);
    }

    public function test_no_results_returns_empty_collection(): void
    {
        $product = Product::factory()->create();
        ProductVariant::create(['product_id' => $product->id, 'type' => 'Size', 'value' => 'M']);

        $response = $this->get(route('products.index') . '?size[]=XXS');
        $products = $response->viewData('products');

        $this->assertCount(0, $products);
    }

    public function test_size_filter_by_variant(): void
    {
        $small  = Product::factory()->create();
        $medium = Product::factory()->create();

        ProductVariant::create(['product_id' => $small->id,  'type' => 'Size', 'value' => 'S']);
        ProductVariant::create(['product_id' => $medium->id, 'type' => 'Size', 'value' => 'M']);

        $response = $this->get(route('products.index') . '?size[]=S');
        $products = $response->viewData('products');

        $this->assertCount(1, $products);
        $this->assertEquals($small->id, $products->first()->id);
    }
}
