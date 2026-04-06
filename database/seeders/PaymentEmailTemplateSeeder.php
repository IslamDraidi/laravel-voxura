<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class PaymentEmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        EmailTemplate::firstOrCreate(
            ['key' => 'payment_failed'],
            [
                'name' => 'Payment Failed',
                'subject' => 'Having trouble paying? We can help — Voxura',
                'body' => '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:32px;">'
                    . '<h2 style="color:#1a1a1a;">Hi {{customer_name}},</h2>'
                    . '<p style="color:#4b5563;line-height:1.6;">We noticed your payment for <strong>Order #{{order_id}}</strong> didn\'t go through.</p>'
                    . '<p style="color:#4b5563;line-height:1.6;"><strong>Reason:</strong> {{error_message}}</p>'
                    . '<p style="color:#4b5563;line-height:1.6;"><strong>Attempt:</strong> {{attempt_count}} of 3</p>'
                    . '<p style="text-align:center;margin:24px 0;"><a href="{{retry_url}}" style="display:inline-block;padding:12px 32px;background:#ea580c;color:#fff;text-decoration:none;border-radius:999px;font-weight:700;">Retry Payment</a></p>'
                    . '<p style="color:#9ca3af;font-size:0.85rem;">If you continue having trouble, please <a href="{{support_url}}" style="color:#ea580c;">contact our support team</a>.</p>'
                    . '</div>',
                'variables' => 'customer_name,order_id,error_message,attempt_count,retry_url,support_url',
            ],
        );

        EmailTemplate::firstOrCreate(
            ['key' => 'payment_blocked'],
            [
                'name' => 'Payment Blocked (Admin)',
                'subject' => '⚠️ Payment Blocked — Order #{{order_id}}',
                'body' => '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:32px;">'
                    . '<h2 style="color:#991b1b;">Payment Blocked Alert</h2>'
                    . '<p style="color:#4b5563;">A customer has reached the maximum payment attempts.</p>'
                    . '<table style="width:100%;border-collapse:collapse;margin:16px 0;">'
                    . '<tr><td style="padding:8px;color:#6b7280;">Customer</td><td style="padding:8px;font-weight:700;">{{customer_name}} ({{customer_email}})</td></tr>'
                    . '<tr><td style="padding:8px;color:#6b7280;">Order</td><td style="padding:8px;font-weight:700;">#{{order_id}}</td></tr>'
                    . '<tr><td style="padding:8px;color:#6b7280;">Amount</td><td style="padding:8px;font-weight:700;">${{total_amount}}</td></tr>'
                    . '<tr><td style="padding:8px;color:#6b7280;">Last Failure</td><td style="padding:8px;font-weight:700;">{{failure_reason}}</td></tr>'
                    . '</table></div>',
                'variables' => 'customer_name,customer_email,order_id,total_amount,failure_reason',
            ],
        );

        EmailTemplate::firstOrCreate(
            ['key' => 'refund_confirmation'],
            [
                'name' => 'Refund Confirmation',
                'subject' => 'Your refund has been processed — Voxura',
                'body' => '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:32px;">'
                    . '<h2 style="color:#1a1a1a;">Hi {{customer_name}},</h2>'
                    . '<p style="color:#4b5563;line-height:1.6;">We\'ve processed a refund for your <strong>Order #{{order_id}}</strong>.</p>'
                    . '<table style="width:100%;border-collapse:collapse;margin:16px 0;background:#f9fafb;border-radius:8px;">'
                    . '<tr><td style="padding:12px;color:#6b7280;">Refund Amount</td><td style="padding:12px;font-weight:700;color:#16a34a;">${{refund_amount}}</td></tr>'
                    . '<tr><td style="padding:12px;color:#6b7280;">Reason</td><td style="padding:12px;">{{refund_reason}}</td></tr>'
                    . '</table>'
                    . '<p style="color:#4b5563;line-height:1.6;">Funds should appear in your account within <strong>5–10 business days</strong> depending on your payment provider.</p>'
                    . '<p style="color:#9ca3af;font-size:0.85rem;">Questions? <a href="{{support_url}}" style="color:#ea580c;">Contact our support team</a>.</p>'
                    . '</div>',
                'variables' => 'customer_name,order_id,refund_amount,refund_reason,support_url',
            ],
        );
    }
}
