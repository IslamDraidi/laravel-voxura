<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class TaxRateResolved
{
    use Dispatchable;

    public function __construct(
        public array $taxRates,
        public array $context,
    ) {}
}
