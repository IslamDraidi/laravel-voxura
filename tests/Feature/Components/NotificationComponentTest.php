<?php

namespace Tests\Feature\Components;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\User;
use App\Notifications\PaymentFailedNotification;
use App\Notifications\RefundConfirmationNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_failed_notification_sent(): void
    {
        Notification::fake();

        $user    = User::factory()->create();
        $order   = Order::factory()->create(['user_id' => $user->id]);
        $payment = Payment::create([
            'order_id'       => $order->id,
            'amount'         => $order->total_amount,
            'status'         => 'failed',
            'failure_reason' => 'Insufficient funds',
            'attempts'       => 1,
        ]);

        $user->notify(new PaymentFailedNotification($order, $payment));

        Notification::assertSentTo($user, PaymentFailedNotification::class);
    }

    public function test_refund_confirmation_notification_sent(): void
    {
        Notification::fake();

        $admin   = User::factory()->create(['role' => 'admin']);
        $user    = User::factory()->create();
        $order   = Order::factory()->create(['user_id' => $user->id]);
        $payment = Payment::create([
            'order_id' => $order->id,
            'amount'   => $order->total_amount,
            'status'   => 'completed',
            'gateway'  => 'tap',
            'attempts' => 1,
        ]);
        $refund = Refund::create([
            'order_id'   => $order->id,
            'payment_id' => $payment->id,
            'admin_id'   => $admin->id,
            'amount'     => 50.00,
            'reason'     => 'Item not as described',
            'status'     => 'completed',
            'gateway'    => 'tap',
        ]);

        $user->notify(new RefundConfirmationNotification($order, $refund));

        Notification::assertSentTo($user, RefundConfirmationNotification::class);
    }

    public function test_password_reset_notification_sent(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $user->sendPasswordResetNotification('fake-token-abc123');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_notification_not_sent_to_wrong_user(): void
    {
        Notification::fake();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $userA->id]);

        $payment = Payment::create([
            'order_id'       => $order->id,
            'amount'         => $order->total_amount,
            'status'         => 'failed',
            'failure_reason' => 'Card declined',
            'attempts'       => 1,
        ]);

        $userA->notify(new PaymentFailedNotification($order, $payment));

        Notification::assertNotSentTo($userB, PaymentFailedNotification::class);
    }
}
