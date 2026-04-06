<?php

namespace Tests\Feature;

use App\Services\Payment\PaymentErrorMapper;
use Tests\TestCase;

class PaymentErrorMapperTest extends TestCase
{
    public function test_maps_paypal_instrument_declined(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('paypal', 'INSTRUMENT_DECLINED');

        $this->assertStringContainsString('declined', $message);
        $this->assertStringNotContainsString('INSTRUMENT_DECLINED', $message);
    }

    public function test_maps_paypal_insufficient_funds(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('paypal', 'INSUFFICIENT_FUNDS');

        $this->assertStringContainsString('Insufficient funds', $message);
    }

    public function test_maps_paypal_invalid_security_code(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('paypal', 'INVALID_SECURITY_CODE');

        $this->assertStringContainsString('CVV', $message);
    }

    public function test_maps_tap_invalid_card(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('tap', 'INVALID_CARD');

        $this->assertStringContainsString('invalid', $message);
    }

    public function test_maps_tap_expired_card(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('tap', 'EXPIRED_CARD');

        $this->assertStringContainsString('expired', $message);
    }

    public function test_maps_tap_insufficient_funds(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('tap', 'INSUFFICIENT_FUNDS');

        $this->assertStringContainsString('Insufficient funds', $message);
    }

    public function test_returns_fallback_for_unknown_paypal_code(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('paypal', 'TOTALLY_UNKNOWN_CODE');

        $this->assertEquals('Payment failed. Please try again or use a different payment method.', $message);
    }

    public function test_returns_fallback_for_unknown_gateway(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('stripe', 'SOME_CODE');

        $this->assertEquals('Payment failed. Please try again or use a different payment method.', $message);
    }

    public function test_returns_fallback_for_null_code(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('paypal', null);

        $this->assertEquals('Payment failed. Please try again or use a different payment method.', $message);
    }

    public function test_handles_lowercase_codes(): void
    {
        $message = PaymentErrorMapper::getFriendlyMessage('paypal', 'instrument_declined');

        $this->assertStringContainsString('declined', $message);
    }
}
