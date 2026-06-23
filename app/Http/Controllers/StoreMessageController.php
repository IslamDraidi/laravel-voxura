<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreMessage;
use App\Models\User;
use App\Notifications\MessageApprovedNotification;
use App\Notifications\AdminMessageReviewNotification;
use App\Services\MessageFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StoreMessageController extends Controller
{
    public function __construct(private MessageFilterService $filter) {}

    // ── Customer: send message ─────────────────────────────────────
    public function send(Request $request, Store $store)
    {
        abort_if($store->status !== 'approved', 404);

        $validated = $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_email' => 'required|email|max:150',
            'subject'        => 'nullable|string|max:200',
            'message'        => 'required|string|min:10|max:2000',
        ]);

        $analysis = $this->filter->analyze(
            $validated['message'],
            $validated['subject'] ?? ''
        );

        $status = $analysis['auto_approve'] ? 'approved' : 'flagged';

        $storeMessage = StoreMessage::create([
            'store_id'       => $store->id,
            'customer_name'  => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'subject'        => $validated['subject'] ?? null,
            'message'        => $validated['message'],
            'status'         => $status,
            'auto_approved'  => $analysis['auto_approve'],
            'filter_flags'   => $analysis['flags'],
        ]);

        Log::info('Store message received', [
            'store'        => $store->slug,
            'status'       => $status,
            'auto_approve' => $analysis['auto_approve'],
            'flags'        => $analysis['flags'],
        ]);

        if ($analysis['auto_approve']) {
            $this->forwardToStore($storeMessage, $store);
        } else {
            $this->notifyAdminForReview($storeMessage, $store);
        }

        return back()->with('message_sent', __('general.message_sent_success'));
    }

    private function forwardToStore(StoreMessage $message, Store $store): void
    {
        $owner = $store->owner;
        if (!$owner) return;

        $owner->notify(new MessageApprovedNotification($message, $store));
        $message->update(['forwarded_at' => now()]);
    }

    private function notifyAdminForReview(StoreMessage $message, Store $store): void
    {
        User::where('role', 'admin')->get()->each(
            fn ($admin) => $admin->notify(new AdminMessageReviewNotification($message, $store))
        );
    }

    // ── Admin: list messages ───────────────────────────────────────
    public function adminIndex()
    {
        $pending = StoreMessage::with('store')
            ->needsReview()
            ->latest()
            ->paginate(20, ['*'], 'pending_page');

        $approved = StoreMessage::with('store')
            ->approved()
            ->latest()
            ->paginate(20, ['*'], 'approved_page');

        $stats = [
            'pending'  => StoreMessage::pending()->count(),
            'flagged'  => StoreMessage::flagged()->count(),
            'approved' => StoreMessage::approved()->count(),
            'today'    => StoreMessage::whereDate('created_at', today())->count(),
        ];

        return view('admin.messages.index', compact('pending', 'approved', 'stats'));
    }

    public function adminShow(StoreMessage $message)
    {
        $message->load('store');
        return view('admin.messages.show', compact('message'));
    }

    // ── Admin: approve ─────────────────────────────────────────────
    public function approve(Request $request, StoreMessage $message)
    {
        $request->validate(['admin_note' => 'nullable|string|max:500']);

        $message->update([
            'status'     => 'approved',
            'admin_note' => $request->admin_note,
        ]);

        $this->forwardToStore($message, $message->store);

        return back()->with('success', 'Message approved and forwarded to store.');
    }

    // ── Admin: reject ──────────────────────────────────────────────
    public function reject(Request $request, StoreMessage $message)
    {
        $request->validate(['admin_note' => 'nullable|string|max:500']);

        $message->update([
            'status'     => 'rejected',
            'admin_note' => $request->admin_note ?? 'Message did not meet our community guidelines.',
        ]);

        return back()->with('success', 'Message rejected.');
    }

    // ── Store owner: view messages ─────────────────────────────────
    public function storeIndex()
    {
        $store = auth()->user()->store;
        abort_if(!$store, 403);

        $messages = StoreMessage::where('store_id', $store->id)
            ->approved()
            ->latest()
            ->paginate(15);

        $unread = StoreMessage::where('store_id', $store->id)
            ->approved()
            ->whereNull('store_reply')
            ->count();

        return view('store.messages.index', compact('messages', 'unread', 'store'));
    }

    // ── Store owner: reply ─────────────────────────────────────────
    public function reply(Request $request, StoreMessage $message)
    {
        $store = auth()->user()->store;
        abort_if(!$store || $message->store_id !== $store->id, 403);

        $validated = $request->validate([
            'store_reply' => 'required|string|min:10|max:2000',
        ]);

        $message->update([
            'store_reply' => $validated['store_reply'],
            'status'      => 'replied',
            'replied_at'  => now(),
        ]);

        Mail::raw(
            "Hello {$message->customer_name},\n\n"
            . "{$store->name} has replied to your message:\n\n"
            . "---\n"
            . $validated['store_reply']
            . "\n---\n\n"
            . "This reply was sent via Voxura.\nVoxura Team",
            function ($mail) use ($message, $store) {
                $mail->to($message->customer_email, $message->customer_name)
                     ->subject("Reply from {$store->name} via Voxura")
                     ->replyTo(config('mail.from.address'), "{$store->name} via Voxura");
            }
        );

        return back()->with('success', 'Reply sent to customer.');
    }
}
