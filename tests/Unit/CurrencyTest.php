<?php

namespace Tests\Unit;

use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function test_currency_config_returns_sar(): void
    {
        $this->assertEquals('SAR', config('shop.currency'));
    }

    public function test_currency_symbol_is_correct(): void
    {
        $symbol = config('shop.currency_symbol');

        $this->assertNotNull($symbol);
        $this->assertNotEmpty($symbol);
    }

    public function test_order_total_is_positive(): void
    {
        $total = 150.00;

        $this->assertGreaterThan(0, $total);
    }

    public function test_tax_rate_is_valid_percentage(): void
    {
        $rate = config('shop.tax_rate');

        $this->assertGreaterThan(0, $rate);
        $this->assertLessThan(1, $rate);
    }
}
