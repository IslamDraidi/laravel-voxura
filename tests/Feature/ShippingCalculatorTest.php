<?php

namespace Tests\Feature;

use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use App\Services\ShippingCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private ShippingCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new ShippingCalculator();
    }

    private function makeMethod(array $attrs = []): ShippingMethod
    {
        return ShippingMethod::create(array_merge([
            'name'       => 'Test Shipping',
            'type'       => 'flat',
            'price'      => 0,
            'base_rate'  => 5.00,
            'is_active'  => true,
            'sort_order' => 0,
        ], $attrs));
    }

    private function baseContext(array $overrides = []): array
    {
        return array_merge([
            'country'      => 'US',
            'region'       => null,
            'channel'      => 'web',
            'order_total'  => 100.00,
            'total_weight' => 2.0,
            'item_count'   => 3,
        ], $overrides);
    }

    public function test_flat_rate_calculation(): void
    {
        $method = $this->makeMethod(['type' => 'flat', 'base_rate' => 7.50]);

        $result = $this->calculator->calculate($method, $this->baseContext());

        $this->assertEquals(7.50, $result['rate']);
        $this->assertFalse($result['free']);
        $this->assertStringContainsString('7.50', $result['label']);
    }

    public function test_per_unit_rate_calculation(): void
    {
        $method = $this->makeMethod([
            'type'          => 'per_unit',
            'base_rate'     => 2.00,
            'per_unit_rate' => 1.50,
        ]);

        // base_rate(2.00) + per_unit_rate(1.50) * item_count(3) = 6.50
        $result = $this->calculator->calculate($method, $this->baseContext(['item_count' => 3]));

        $this->assertEquals(6.50, $result['rate']);
    }

    public function test_weight_based_calculation(): void
    {
        $method = $this->makeMethod([
            'type'        => 'weight_based',
            'base_rate'   => 3.00,
            'weight_rate' => 2.00,
        ]);

        // base_rate(3.00) + weight_rate(2.00) * total_weight(2.5) = 8.00
        $result = $this->calculator->calculate($method, $this->baseContext(['total_weight' => 2.5]));

        $this->assertEquals(8.00, $result['rate']);
    }

    public function test_free_shipping_type(): void
    {
        $method = $this->makeMethod(['type' => 'free', 'base_rate' => 0]);

        $result = $this->calculator->calculate($method, $this->baseContext());

        $this->assertEquals(0.00, $result['rate']);
        $this->assertTrue($result['free']);
    }

    public function test_free_shipping_threshold(): void
    {
        $method = $this->makeMethod([
            'type'       => 'flat',
            'base_rate'  => 9.99,
            'free_above' => 80.00,
        ]);

        // order_total 100 > free_above 80 → free
        $result = $this->calculator->calculate($method, $this->baseContext(['order_total' => 100.00]));

        $this->assertEquals(0.00, $result['rate']);
        $this->assertTrue($result['free']);
    }

    public function test_free_threshold_not_met(): void
    {
        $method = $this->makeMethod([
            'type'       => 'flat',
            'base_rate'  => 9.99,
            'free_above' => 150.00,
        ]);

        $result = $this->calculator->calculate($method, $this->baseContext(['order_total' => 100.00]));

        $this->assertEquals(9.99, $result['rate']);
        $this->assertFalse($result['free']);
    }

    public function test_zone_rate_override(): void
    {
        $method = $this->makeMethod(['type' => 'flat', 'base_rate' => 10.00]);

        $zone = ShippingZone::create([
            'name'      => 'Middle East',
            'countries' => ['JO', 'AE'],
            'is_active' => true,
        ]);

        $zone->methods()->attach($method->id, ['rate_override' => 4.00, 'is_active' => true]);
        $method->load('zones');

        $result = $this->calculator->calculate($method, $this->baseContext(['country' => 'JO']), $zone);

        $this->assertEquals(4.00, $result['rate']);
    }

    public function test_channel_filtering(): void
    {
        $this->makeMethod(['type' => 'flat', 'base_rate' => 5.00, 'channel' => 'web']);
        $this->makeMethod(['type' => 'flat', 'base_rate' => 6.00, 'channel' => 'mobile', 'name' => 'Mobile Only']);
        $this->makeMethod(['type' => 'flat', 'base_rate' => 7.00, 'channel' => null,     'name' => 'All Channels']);

        $webMethods    = $this->calculator->getAvailableMethods($this->baseContext(['channel' => 'web']));
        $mobileMethods = $this->calculator->getAvailableMethods($this->baseContext(['channel' => 'mobile']));

        $webNames    = $webMethods->pluck('name')->toArray();
        $mobileNames = $mobileMethods->pluck('name')->toArray();

        $this->assertContains('Test Shipping', $webNames);
        $this->assertContains('All Channels', $webNames);
        $this->assertNotContains('Mobile Only', $webNames);

        $this->assertContains('Mobile Only', $mobileNames);
        $this->assertContains('All Channels', $mobileNames);
        $this->assertNotContains('Test Shipping', $mobileNames);
    }

    public function test_min_order_amount_filter(): void
    {
        $this->makeMethod(['type' => 'flat', 'base_rate' => 5.00, 'min_order_amount' => 50.00]);

        $methods = $this->calculator->getAvailableMethods($this->baseContext(['order_total' => 30.00]));

        $this->assertEmpty($methods);
    }

    public function test_max_order_amount_filter(): void
    {
        $this->makeMethod(['type' => 'flat', 'base_rate' => 5.00, 'max_order_amount' => 50.00]);

        $methods = $this->calculator->getAvailableMethods($this->baseContext(['order_total' => 100.00]));

        $this->assertEmpty($methods);
    }

    public function test_estimated_delivery_text(): void
    {
        $method = $this->makeMethod([
            'type'               => 'flat',
            'base_rate'          => 5.00,
            'estimated_days_min' => 3,
            'estimated_days_max' => 5,
        ]);

        $result = $this->calculator->calculate($method, $this->baseContext());

        $this->assertEquals('3-5 business days', $result['estimated_delivery']);
    }

    public function test_resolve_zone_by_country(): void
    {
        ShippingZone::create([
            'name'      => 'Europe',
            'countries' => ['DE', 'FR', 'GB'],
            'is_active' => true,
        ]);

        $zone = $this->calculator->resolveZone('DE');

        $this->assertNotNull($zone);
        $this->assertEquals('Europe', $zone->name);
    }

    public function test_resolve_zone_returns_null_for_unknown_country(): void
    {
        $zone = $this->calculator->resolveZone('XX');

        $this->assertNull($zone);
    }

    public function test_inactive_methods_excluded(): void
    {
        $this->makeMethod(['type' => 'flat', 'base_rate' => 5.00, 'is_active' => false]);

        $methods = $this->calculator->getAvailableMethods($this->baseContext());

        $this->assertEmpty($methods);
    }
}
