<?php

namespace App\Providers;

use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\PayPalPaymentService;
use App\Services\Payment\TapPaymentService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('payment.gateway', function ($app, array $params = []) {
            $gateway = $params['gateway'] ?? config('payment.default');

            return match ($gateway) {
                'paypal' => new PayPalPaymentService(),
                'tap' => new TapPaymentService(),
                default => throw new \InvalidArgumentException("Unknown payment gateway: {$gateway}"),
            };
        });

        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            return $app->make('payment.gateway');
        });
    }
}
