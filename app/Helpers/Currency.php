<?php

namespace App\Helpers;

use NumberFormatter;

class Currency
{
    public static function format(float $amount, string $currency = 'USD', ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if (class_exists(NumberFormatter::class)) {
            $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
            $result = $formatter->formatCurrency($amount, $currency);
            if ($result !== false) {
                return $result;
            }
        }

        return $currency.' '.number_format($amount, 2);
    }
}
