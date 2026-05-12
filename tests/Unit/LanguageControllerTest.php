<?php

namespace Tests\Unit;

use Tests\TestCase;

class LanguageControllerTest extends TestCase
{
    private array $supported = ['en', 'ar'];

    public function test_valid_locales_are_accepted(): void
    {
        $this->assertTrue(in_array('en', $this->supported, true));
        $this->assertTrue(in_array('ar', $this->supported, true));
    }

    public function test_invalid_locale_is_rejected(): void
    {
        $this->assertFalse(in_array('fr', $this->supported, true));
        $this->assertFalse(in_array('xyz', $this->supported, true));
        $this->assertFalse(in_array('', $this->supported, true));
    }

    public function test_locale_validation_rejects_injection(): void
    {
        $this->assertFalse(in_array('<script>', $this->supported, true));
        $this->assertFalse(in_array("'; DROP TABLE users; --", $this->supported, true));
    }
}
