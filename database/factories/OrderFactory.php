<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 500);

        return [
            'user_id'         => User::factory(),
            'status'          => 'pending',
            'currency'        => 'SAR',
            'subtotal'        => $subtotal,
            'total_amount'    => $subtotal,
            'grand_total'     => $subtotal,
            'discount_amount' => 0,
            'tax_amount'      => 0,
            'shipping_cost'   => 0,
        ];
    }
}
