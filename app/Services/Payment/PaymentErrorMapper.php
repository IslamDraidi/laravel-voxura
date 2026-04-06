<?php

namespace App\Services\Payment;

class PaymentErrorMapper
{
    private static array $paypalMessages = [
        'INSTRUMENT_DECLINED' => 'Your payment method was declined. Please try a different card or payment method.',
        'INSUFFICIENT_FUNDS' => 'Insufficient funds. Please use a different payment method.',
        'INVALID_SECURITY_CODE' => 'The security code (CVV) is incorrect. Please check your card details and try again.',
        'PAYER_ACTION_REQUIRED' => 'Additional action is required by your bank. Please try again.',
        'TRANSACTION_REFUSED' => 'The transaction was refused. Please try a different payment method.',
        'CARD_EXPIRED' => 'Your card has expired. Please use a different card.',
        'INVALID_ACCOUNT' => 'There is an issue with your account. Please contact your bank or use a different method.',
        'PAYER_CANNOT_PAY' => 'This payment method cannot be used for this purchase. Please try another.',
        'MAX_NUMBER_OF_PAYMENT_ATTEMPTS_EXCEEDED' => 'Too many payment attempts. Please try again later.',
    ];

    private static array $tapMessages = [
        'INVALID_CARD' => 'Your card details are invalid. Please check and try again.',
        'EXPIRED_CARD' => 'Your card has expired. Please use a different card.',
        'INSUFFICIENT_FUNDS' => 'Insufficient funds. Please use a different payment method.',
        'RESTRICTED_CARD' => 'This card is restricted. Please use a different card.',
        'STOLEN_CARD' => 'This card cannot be used. Please contact your bank.',
        'DECLINED' => 'Your payment was declined. Please try a different payment method.',
        'AUTHENTICATION_FAILED' => '3D Secure authentication failed. Please try again.',
        'NOT_CAPTURED' => 'Payment could not be captured. Please try again.',
    ];

    private static string $fallback = 'Payment failed. Please try again or use a different payment method.';

    public static function getFriendlyMessage(string $gateway, ?string $code): string
    {
        if (! $code) {
            return self::$fallback;
        }

        $code = strtoupper($code);

        return match ($gateway) {
            'paypal' => self::$paypalMessages[$code] ?? self::$fallback,
            'tap' => self::$tapMessages[$code] ?? self::$fallback,
            default => self::$fallback,
        };
    }
}
