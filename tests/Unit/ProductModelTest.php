<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    public function test_localized_name_returns_arabic_when_locale_ar(): void
    {
        $p           = new Product();
        $p->name     = 'Black Hoodie';
        $p->name_ar  = 'هودي أسود';

        App::setLocale('ar');

        $this->assertEquals('هودي أسود', $p->localized_name);
    }

    public function test_localized_name_falls_back_to_english(): void
    {
        $p          = new Product();
        $p->name    = 'Black Hoodie';
        $p->name_ar = null;

        App::setLocale('ar');

        $this->assertEquals('Black Hoodie', $p->localized_name);
    }

    public function test_localized_name_returns_english_when_locale_en(): void
    {
        $p          = new Product();
        $p->name    = 'Black Hoodie';
        $p->name_ar = 'هودي أسود';

        App::setLocale('en');

        $this->assertEquals('Black Hoodie', $p->localized_name);
    }

    public function test_localized_description_returns_arabic(): void
    {
        $p                 = new Product();
        $p->description    = 'A warm hoodie.';
        $p->description_ar = 'هودي دافئ.';

        App::setLocale('ar');

        $this->assertEquals('هودي دافئ.', $p->localized_description);
    }

    public function test_stock_urgency_threshold(): void
    {
        $low        = new Product();
        $low->stock = 4;
        $this->assertTrue($low->is_low_stock);

        $ok        = new Product();
        $ok->stock = 5;
        $this->assertFalse($ok->is_low_stock);

        $empty        = new Product();
        $empty->stock = 0;
        $this->assertFalse($empty->is_low_stock);
    }

    public function test_product_is_out_of_stock(): void
    {
        $out        = new Product();
        $out->stock = 0;
        $this->assertFalse($out->in_stock);

        $in        = new Product();
        $in->stock = 1;
        $this->assertTrue($in->in_stock);
    }
}
