<?php

namespace App\Services;

use App\Events\ShippingRateRequested;
use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use Illuminate\Support\Collection;

class ShippingCalculator
{
    public function getAvailableMethods(array $context): Collection
    {
        $zone = $this->resolveZone($context['country'] ?? '', $context['region'] ?? null);

        $query = ShippingMethod::active()
            ->forChannel($context['channel'] ?? 'web')
            ->ordered();

        if ($zone) {
            $query->whereHas('zones', fn ($q) => $q->where('shipping_zones.id', $zone->id)
                ->where('shipping_zone_method.is_active', true));
        }

        $methods = $query->get();

        return $methods->filter(function (ShippingMethod $method) use ($context) {
            if ($method->min_order_amount && $context['order_total'] < (float) $method->min_order_amount) {
                return false;
            }
            if ($method->max_order_amount && $context['order_total'] > (float) $method->max_order_amount) {
                return false;
            }
            if ($method->max_weight && ($context['total_weight'] ?? 0) > (float) $method->max_weight) {
                return false;
            }

            return true;
        })->map(function (ShippingMethod $method) use ($context, $zone) {
            $calculated = $this->calculate($method, $context, $zone);
            $method->setAttribute('calculated_rate', $calculated['rate']);
            $method->setAttribute('calculated_label', $calculated['label']);
            $method->setAttribute('calculated_free', $calculated['free']);
            $method->setAttribute('estimated_delivery_text', $calculated['estimated_delivery']);

            return $method;
        })->values();
    }

    public function calculate(ShippingMethod $method, array $context, ?ShippingZone $zone = null): array
    {
        $rate = $this->calculateBaseRate($method, $context);

        // Apply zone rate override
        if ($zone) {
            $pivot = $method->zones->firstWhere('id', $zone->id);
            if ($pivot && $pivot->pivot->rate_override !== null) {
                $rate = (float) $pivot->pivot->rate_override;
            }
        }

        // Apply free_above threshold
        $free = false;
        if ($method->type === 'free') {
            $free = true;
            $rate = 0.00;
        } elseif ($method->free_above && ($context['order_total'] ?? 0) >= (float) $method->free_above) {
            $free = true;
            $rate = 0.00;
        }

        $rate = round($rate, 2);

        $label = $method->translated_name.' - '.($free ? 'Free' : '$'.number_format($rate, 2));

        $delivery = null;
        if ($method->estimated_days_min && $method->estimated_days_max) {
            $delivery = $method->estimated_days_min.'-'.$method->estimated_days_max.' business days';
        } elseif ($method->estimated_days_min) {
            $delivery = $method->estimated_days_min.'+ business days';
        } elseif ($method->estimated_delivery) {
            $delivery = $method->estimated_delivery;
        }

        return [
            'rate' => $rate,
            'label' => $label,
            'free' => $free,
            'estimated_delivery' => $delivery,
        ];
    }

    public function resolveZone(string $country, ?string $region = null): ?ShippingZone
    {
        if (empty($country)) {
            return null;
        }

        $zones = ShippingZone::active()->get();

        // Try exact match with region first
        if ($region) {
            foreach ($zones as $zone) {
                if ($zone->containsCountry($country) && $zone->containsRegion($country, $region)) {
                    return $zone;
                }
            }
        }

        // Fall back to country-only match
        foreach ($zones as $zone) {
            if ($zone->containsCountry($country)) {
                return $zone;
            }
        }

        return null;
    }

    protected function calculateBaseRate(ShippingMethod $method, array $context): float
    {
        return match ($method->type) {
            'flat' => (float) $method->base_rate,
            'per_unit' => (float) $method->base_rate + ((float) $method->per_unit_rate * ($context['item_count'] ?? 1)),
            'weight_based' => (float) $method->base_rate + ((float) $method->weight_rate * ($context['total_weight'] ?? 0)),
            'free' => 0.00,
            'custom' => $this->resolveCustomRate($method, $context),
            default => (float) $method->base_rate,
        };
    }

    protected function resolveCustomRate(ShippingMethod $method, array $context): float
    {
        $event = new ShippingRateRequested($method, $context);
        event($event);

        return $event->rate ?? (float) $method->base_rate;
    }
}
