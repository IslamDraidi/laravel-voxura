<?php

namespace App\Events;

use App\Models\ShippingMethod;
use Illuminate\Foundation\Events\Dispatchable;

class ShippingRateRequested
{
    use Dispatchable;

    public ?float $rate = null;

    public function __construct(
        public ShippingMethod $method,
        public array $context,
    ) {}
}
