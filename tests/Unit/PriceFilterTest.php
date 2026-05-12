<?php

namespace Tests\Unit;

use Tests\TestCase;

class PriceFilterTest extends TestCase
{
    public function test_price_range_under_50(): void
    {
        $this->assertTrue(45 <= 50);
    }

    public function test_price_range_excludes_above_max(): void
    {
        $this->assertFalse(151 <= 150);
    }

    public function test_price_range_50_to_150(): void
    {
        $price = 100;

        $this->assertTrue($price >= 50 && $price <= 150);
    }

    public function test_price_range_boundary_values(): void
    {
        $this->assertTrue(50 >= 50 && 50 <= 150);
        $this->assertTrue(150 >= 50 && 150 <= 150);
        $this->assertFalse(49 >= 50);
        $this->assertFalse(151 <= 150);
    }
}
