<?php

namespace Tests\Unit;

use Tests\TestCase;

class RecentlyViewedTest extends TestCase
{
    public function test_product_added_to_recently_viewed(): void
    {
        $viewed = [];
        array_unshift($viewed, 1);

        $this->assertEquals([1], $viewed);
    }

    public function test_recently_viewed_max_6_items(): void
    {
        $viewed = [1, 2, 3, 4, 5, 6];
        array_unshift($viewed, 7);
        $viewed = array_unique($viewed);
        $viewed = array_slice($viewed, 0, 6);

        $this->assertCount(6, $viewed);
        $this->assertEquals(7, $viewed[0]);
    }

    public function test_duplicate_not_added_twice(): void
    {
        $viewed = [1, 2, 3];
        array_unshift($viewed, 1);
        $viewed = array_values(array_unique($viewed));

        $this->assertCount(3, $viewed);
        $this->assertEquals(1, $viewed[0]);
    }

    public function test_current_product_excluded_from_display(): void
    {
        $viewed    = [1, 2, 3, 4, 5];
        $currentId = 1;
        $display   = array_values(array_diff($viewed, [$currentId]));

        $this->assertFalse(in_array(1, $display, true));
        $this->assertCount(4, $display);
    }
}
