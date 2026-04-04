<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('subject');
            $table->longText('body');
            $table->text('variables')->nullable()->comment('Comma-separated list of available variable names');
            $table->timestamps();
        });

        DB::table('email_templates')->insert([
            [
                'key' => 'order_confirmation',
                'name' => 'Order Confirmation',
                'subject' => 'Your order #{{order_id}} has been placed!',
                'body' => <<<'HTML'
<!DOCTYPE html>
<html>
<body style="font-family: 'DM Sans', Arial, sans-serif; background:#f9fafb; margin:0; padding:2rem;">
  <div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;padding:2rem;border:1.5px solid #e5e7eb;">
    <h1 style="font-family:Georgia,serif;color:#ea580c;margin-top:0;">Order Confirmed!</h1>
    <p style="color:#374151;">Hi <strong>{{customer_name}}</strong>,</p>
    <p style="color:#374151;">Thank you for your order. We've received it and will begin processing it shortly.</p>
    <table style="width:100%;border-collapse:collapse;margin:1.5rem 0;">
      <tr style="background:#f9fafb;">
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Order ID</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#111827;">#{{order_id}}</td>
      </tr>
      <tr>
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Order Date</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#111827;">{{order_date}}</td>
      </tr>
      <tr style="background:#f9fafb;">
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Total</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#ea580c;">{{order_total}}</td>
      </tr>
      <tr>
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Status</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#111827;">{{order_status}}</td>
      </tr>
    </table>
    <a href="{{order_url}}" style="display:inline-block;background:#ea580c;color:#fff;padding:0.7rem 1.5rem;border-radius:999px;text-decoration:none;font-weight:700;font-size:0.9rem;">View Order</a>
    <p style="color:#9ca3af;font-size:0.8rem;margin-top:2rem;">{{store_name}} &mdash; Thank you for shopping with us.</p>
  </div>
</body>
</html>
HTML,
                'variables' => 'customer_name, order_id, order_date, order_total, order_status, order_url, store_name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'shipping_notification',
                'name' => 'Shipping Notification',
                'subject' => 'Your order #{{order_id}} has shipped!',
                'body' => <<<'HTML'
<!DOCTYPE html>
<html>
<body style="font-family: 'DM Sans', Arial, sans-serif; background:#f9fafb; margin:0; padding:2rem;">
  <div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;padding:2rem;border:1.5px solid #e5e7eb;">
    <h1 style="font-family:Georgia,serif;color:#ea580c;margin-top:0;">Your Order is On Its Way! 🚚</h1>
    <p style="color:#374151;">Hi <strong>{{customer_name}}</strong>,</p>
    <p style="color:#374151;">Great news! Your order <strong>#{{order_id}}</strong> has been shipped and is on its way to you.</p>
    <table style="width:100%;border-collapse:collapse;margin:1.5rem 0;">
      <tr style="background:#f9fafb;">
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Tracking Number</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#111827;">{{tracking_number}}</td>
      </tr>
      <tr>
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Carrier</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#111827;">{{carrier}}</td>
      </tr>
      <tr style="background:#f9fafb;">
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Estimated Delivery</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#ea580c;">{{estimated_delivery}}</td>
      </tr>
    </table>
    <a href="{{order_url}}" style="display:inline-block;background:#ea580c;color:#fff;padding:0.7rem 1.5rem;border-radius:999px;text-decoration:none;font-weight:700;font-size:0.9rem;">Track Order</a>
    <p style="color:#9ca3af;font-size:0.8rem;margin-top:2rem;">{{store_name}} &mdash; Thank you for your patience.</p>
  </div>
</body>
</html>
HTML,
                'variables' => 'customer_name, order_id, tracking_number, carrier, estimated_delivery, order_url, store_name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'welcome',
                'name' => 'Welcome Email',
                'subject' => 'Welcome to {{store_name}}, {{customer_name}}!',
                'body' => <<<'HTML'
<!DOCTYPE html>
<html>
<body style="font-family: 'DM Sans', Arial, sans-serif; background:#f9fafb; margin:0; padding:2rem;">
  <div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;padding:2rem;border:1.5px solid #e5e7eb;">
    <h1 style="font-family:Georgia,serif;color:#ea580c;margin-top:0;">Welcome, {{customer_name}}! 🎉</h1>
    <p style="color:#374151;">We're thrilled to have you join the <strong>{{store_name}}</strong> family.</p>
    <p style="color:#374151;">Your account has been created and you can now browse our full catalog, save favourites, and track your orders.</p>
    <a href="{{shop_url}}" style="display:inline-block;background:#ea580c;color:#fff;padding:0.7rem 1.5rem;border-radius:999px;text-decoration:none;font-weight:700;font-size:0.9rem;">Start Shopping</a>
    <p style="color:#9ca3af;font-size:0.8rem;margin-top:2rem;">{{store_name}} &mdash; Happy shopping!</p>
  </div>
</body>
</html>
HTML,
                'variables' => 'customer_name, store_name, shop_url',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'order_cancelled',
                'name' => 'Order Cancelled',
                'subject' => 'Your order #{{order_id}} has been cancelled',
                'body' => <<<'HTML'
<!DOCTYPE html>
<html>
<body style="font-family: 'DM Sans', Arial, sans-serif; background:#f9fafb; margin:0; padding:2rem;">
  <div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;padding:2rem;border:1.5px solid #e5e7eb;">
    <h1 style="font-family:Georgia,serif;color:#374151;margin-top:0;">Order Cancelled</h1>
    <p style="color:#374151;">Hi <strong>{{customer_name}}</strong>,</p>
    <p style="color:#374151;">Your order <strong>#{{order_id}}</strong> has been cancelled. If you did not request this cancellation or have any questions, please contact our support team.</p>
    <table style="width:100%;border-collapse:collapse;margin:1.5rem 0;">
      <tr style="background:#f9fafb;">
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Order ID</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#111827;">#{{order_id}}</td>
      </tr>
      <tr>
        <td style="padding:0.6rem 1rem;font-size:0.85rem;color:#6b7280;">Refund Amount</td>
        <td style="padding:0.6rem 1rem;font-weight:700;color:#111827;">{{refund_amount}}</td>
      </tr>
    </table>
    <a href="{{shop_url}}" style="display:inline-block;background:#ea580c;color:#fff;padding:0.7rem 1.5rem;border-radius:999px;text-decoration:none;font-weight:700;font-size:0.9rem;">Continue Shopping</a>
    <p style="color:#9ca3af;font-size:0.8rem;margin-top:2rem;">{{store_name}}</p>
  </div>
</body>
</html>
HTML,
                'variables' => 'customer_name, order_id, refund_amount, shop_url, store_name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
