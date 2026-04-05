<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\TaxRate;
use App\Services\TaxCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private TaxCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new TaxCalculator();
    }

    private function makeRate(array $attrs = []): TaxRate
    {
        return TaxRate::create(array_merge([
            'name'             => 'Test Tax',
            'rate'             => 10.00,
            'type'             => 'percentage',
            'scope'            => 'product',
            'priority'         => 0,
            'is_inclusive'     => false,
            'apply_to_shipping'=> false,
            'is_active'        => true,
        ], $attrs));
    }

    private function items(array $overrides = []): array
    {
        return [
            array_merge([
                'product_id'  => 1,
                'category_id' => 1,
                'price'       => 50.00,
                'qty'         => 2,
            ], $overrides),
        ];
    }

    private function baseContext(array $overrides = []): array
    {
        return array_merge([
            'country'         => 'US',
            'channel'         => 'web',
            'items'           => $this->items(),
            'shipping_amount' => 5.00,
        ], $overrides);
    }

    public function test_percentage_tax_on_items(): void
    {
        $this->makeRate(['rate' => 16.00, 'scope' => 'product']);

        // subtotal = 50 * 2 = 100; tax = 100 * 16% = 16
        $result = $this->calculator->calculate($this->baseContext());

        $this->assertEquals(16.00, $result['tax_amount']);
        $this->assertCount(1, $result['breakdown']);
        $this->assertEquals('product', $result['breakdown'][0]['scope']);
    }

    public function test_fixed_tax_on_order(): void
    {
        $this->makeRate(['rate' => 5.00, 'type' => 'fixed', 'scope' => 'order']);

        $result = $this->calculator->calculate($this->baseContext());

        $this->assertEquals(5.00, $result['tax_amount']);
    }

    public function test_category_scoped_tax(): void
    {
        $category = Category::create(['name' => 'Apparel', 'slug' => 'apparel']);

        $this->makeRate([
            'rate'        => 10.00,
            'scope'       => 'category',
            'category_id' => $category->id,
        ]);

        // item in matching category: price 50 * qty 2 = 100; tax = 10%
        $matchingItems = [[
            'product_id'  => 1,
            'category_id' => $category->id,
            'price'       => 50.00,
            'qty'         => 2,
        ]];

        $result = $this->calculator->calculate($this->baseContext(['items' => $matchingItems]));
        $this->assertEquals(10.00, $result['tax_amount']);

        // item in a different category → 0 tax
        $otherItems = [[
            'product_id'  => 2,
            'category_id' => 999,
            'price'       => 50.00,
            'qty'         => 2,
        ]];

        $result2 = $this->calculator->calculate($this->baseContext(['items' => $otherItems]));
        $this->assertEquals(0.00, $result2['tax_amount']);
    }

    public function test_compound_tax_ordering(): void
    {
        // Base Tax: 10% percentage on subtotal(100) = 10; does NOT update compound base
        // Compound Tax: 5% compound on subtotal(100) = 5 (compounds on top of itself for next)
        // Total = 10 + 5 = 15
        $this->makeRate(['name' => 'Base Tax', 'rate' => 10.00, 'type' => 'percentage', 'priority' => 0]);
        $this->makeRate(['name' => 'Compound Tax', 'rate' => 5.00, 'type' => 'compound', 'priority' => 1]);

        $result = $this->calculator->calculate($this->baseContext());

        $this->assertEquals(15.00, $result['tax_amount']);
        $this->assertCount(2, $result['breakdown']);
    }

    public function test_stacked_compound_taxes(): void
    {
        // Two compound taxes — second applies on (subtotal + first compound tax)
        // Compound 1: 10% of 100 = 10; updates subtotal to 110
        // Compound 2: 5% of 110 = 5.50; total = 15.50
        $this->makeRate(['name' => 'Compound 1', 'rate' => 10.00, 'type' => 'compound', 'priority' => 0]);
        $this->makeRate(['name' => 'Compound 2', 'rate' => 5.00,  'type' => 'compound', 'priority' => 1]);

        $result = $this->calculator->calculate($this->baseContext());

        $this->assertEquals(15.50, $result['tax_amount']);
        $this->assertCount(2, $result['breakdown']);
    }

    public function test_inclusive_tax_back_calculation(): void
    {
        // Price includes 10% tax. subtotal=100, back-calc: 100 - (100/1.1) = 9.09
        $this->makeRate(['rate' => 10.00, 'scope' => 'product', 'is_inclusive' => true]);

        $result = $this->calculator->calculate($this->baseContext());

        $this->assertEqualsWithDelta(9.09, $result['tax_amount'], 0.01);
        $this->assertTrue($result['inclusive']);
    }

    public function test_tax_applied_to_shipping(): void
    {
        $this->makeRate([
            'rate'              => 10.00,
            'scope'             => 'product',
            'apply_to_shipping' => true,
        ]);

        // shipping tax = 5.00 * 10% = 0.50
        $result = $this->calculator->calculate($this->baseContext(['shipping_amount' => 5.00]));

        $this->assertEquals(0.50, $result['shipping_tax_amount']);
    }

    public function test_shipping_scope_tax(): void
    {
        $this->makeRate(['rate' => 20.00, 'scope' => 'shipping']);

        // shipping = 5.00, tax = 5 * 20% = 1.00; no item tax
        $result = $this->calculator->calculate($this->baseContext(['shipping_amount' => 5.00]));

        $this->assertEquals(0.00, $result['tax_amount']);
        $this->assertEquals(1.00, $result['shipping_tax_amount']);
    }

    public function test_validity_date_filtering(): void
    {
        // Valid rate
        $this->makeRate([
            'rate'       => 10.00,
            'valid_from' => now()->subDay(),
            'valid_to'   => now()->addDay(),
        ]);

        // Expired rate
        $this->makeRate([
            'name'     => 'Expired Tax',
            'rate'     => 20.00,
            'valid_to' => now()->subDay(),
        ]);

        // Future rate
        $this->makeRate([
            'name'       => 'Future Tax',
            'rate'       => 30.00,
            'valid_from' => now()->addDay(),
        ]);

        $result = $this->calculator->calculate($this->baseContext());

        // Only the valid rate applies: 10% of 100 = 10
        $this->assertEquals(10.00, $result['tax_amount']);
        $this->assertCount(1, $result['breakdown']);
    }

    public function test_inactive_rates_excluded(): void
    {
        $this->makeRate(['rate' => 15.00, 'is_active' => false]);

        $result = $this->calculator->calculate($this->baseContext());

        $this->assertEquals(0.00, $result['tax_amount']);
        $this->assertEmpty($result['breakdown']);
    }

    public function test_no_rates_returns_zero(): void
    {
        $result = $this->calculator->calculate($this->baseContext());

        $this->assertEquals(0.00, $result['tax_amount']);
        $this->assertEquals(0.00, $result['shipping_tax_amount']);
        $this->assertEmpty($result['breakdown']);
    }

    public function test_breakdown_contains_correct_fields(): void
    {
        $this->makeRate(['name' => 'VAT', 'rate' => 16.00]);

        $result = $this->calculator->calculate($this->baseContext());

        $entry = $result['breakdown'][0];
        $this->assertArrayHasKey('name', $entry);
        $this->assertArrayHasKey('rate', $entry);
        $this->assertArrayHasKey('amount', $entry);
        $this->assertArrayHasKey('scope', $entry);
        $this->assertArrayHasKey('inclusive', $entry);
        $this->assertEquals('VAT', $entry['name']);
        $this->assertEquals(16.00, $entry['rate']);
    }
}
