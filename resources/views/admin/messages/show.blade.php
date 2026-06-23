<x-admin-layout title="Message Detail" section="messages" active="messages">

<style>
.detail-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:20px;}
.detail-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);margin-bottom:4px;}
.detail-value{font-size:14px;color:var(--dark);}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;}
.message-box{background:var(--gray-50);border:1px solid var(--border);border-radius:10px;padding:18px;font-size:14px;color:var(--dark);line-height:1.7;white-space:pre-wrap;}
.flag-pill{display:inline-block;background:#fee2e2;color:#991b1b;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600;margin:2px;}
.badge{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#92400e;}
.badge-flagged{background:#fee2e2;color:#991b1b;}
.badge-approved{background:#dcfce7;color:#15803d;}
.badge-rejected{background:#f3f4f6;color:#374151;}
.badge-replied{background:#dbeafe;color:#1d4ed8;}
.btn-action{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:8px;font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:inherit;text-decoration:none;}
.btn-green{background:#16a34a;color:#fff;}
.btn-green:hover{background:#15803d;}
.btn-red{background:#dc2626;color:#fff;}
.btn-red:hover{background:#b91c1c;}
.btn-gray{background:var(--gray-100);color:var(--gray-700);}
.btn-gray:hover{background:var(--gray-200);}
.reply-box{background:#dcfce7;border:1px solid #16a34a;border-radius:10px;padding:16px 18px;}
</style>

<div style="margin-bottom:16px;">
    <a href="{{ route('admin.messages.index') }}" class="btn-action btn-gray">
        <i class="ti ti-arrow-left"></i> Back to Messages
    </a>
</div>

@if(session('success'))
<div style="background:#dcfce7;border:1px solid #16a34a;border-radius:8px;padding:12px 16px;color:#15803d;font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
    <i class="ti ti-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Store info --}}
<div class="detail-card">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
        <div style="width:48px;height:48px;border-radius:50%;background:#1a1a1a;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:16px;flex-shrink:0;">
            {{ strtoupper(substr($message->store->name ?? 'S', 0, 2)) }}
        </div>
        <div>
            <div style="font-size:16px;font-weight:700;color:var(--dark);">{{ $message->store->name ?? '—' }}</div>
            <a href="{{ route('stores.show', $message->store) }}" style="font-size:12px;color:var(--orange);" target="_blank">
                View store page →
            </a>
        </div>
        <div style="margin-left:auto;">
            @if($message->status === 'pending')
            <span class="badge badge-pending"><i class="ti ti-clock"></i> Pending</span>
            @elseif($message->status === 'flagged')
            <span class="badge badge-flagged"><i class="ti ti-flag"></i> Flagged</span>
            @elseif($message->status === 'approved')
            <span class="badge badge-approved"><i class="ti ti-check"></i> Approved</span>
            @elseif($message->status === 'rejected')
            <span class="badge badge-rejected"><i class="ti ti-x"></i> Rejected</span>
            @elseif($message->status === 'replied')
            <span class="badge badge-replied"><i class="ti ti-message"></i> Replied</span>
            @endif
        </div>
    </div>

    <div class="detail-grid">
        <div>
            <div class="detail-label">Customer Name</div>
            <div class="detail-value">{{ $message->customer_name }}</div>
        </div>
        <div>
            <div class="detail-label">Customer Email</div>
            <div class="detail-value">{{ $message->customer_email }}</div>
        </div>
        <div>
            <div class="detail-label">Subject</div>
            <div class="detail-value">{{ $message->subject ?? 'General inquiry' }}</div>
        </div>
        <div>
            <div class="detail-label">Sent</div>
            <div class="detail-value">{{ $message->created_at->format('M d, Y H:i') }}</div>
        </div>
    </div>

    <div class="detail-label" style="margin-bottom:8px;">Message</div>
    <div class="message-box">{{ $message->message }}</div>
</div>

{{-- Filter analysis --}}
<div class="detail-card">
    <div style="font-size:14px;font-weight:700;color:var(--dark);margin-bottom:14px;">Filter Analysis</div>

    <div class="detail-grid">
        <div>
            <div class="detail-label">Auto Approved</div>
            <div class="detail-value">
                @if($message->auto_approved)
                <span style="color:#16a34a;font-weight:600;">✓ Yes</span>
                @else
                <span style="color:#dc2626;font-weight:600;">✗ No</span>
                @endif
            </div>
        </div>
        <div>
            <div class="detail-label">Word Count</div>
            <div class="detail-value">{{ str_word_count($message->message) }} words</div>
        </div>
    </div>

    <div class="detail-label" style="margin-bottom:6px;">Flags</div>
    @if(!empty($message->filter_flags))
        @foreach($message->filter_flags as $flag)
        <span class="flag-pill">{{ $flag }}</span>
        @endforeach
    @else
        <span style="font-size:13px;color:var(--muted);">No flags</span>
    @endif

    @if($message->admin_note)
    <div style="margin-top:16px;">
        <div class="detail-label" style="margin-bottom:6px;">Admin Note</div>
        <div class="message-box">{{ $message->admin_note }}</div>
    </div>
    @endif
</div>

{{-- Actions --}}
@if(in_array($message->status, ['pending', 'flagged']))
<div class="detail-card">
    <div style="font-size:14px;font-weight:700;color:var(--dark);margin-bottom:16px;">Actions</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <form action="{{ route('admin.messages.approve', $message) }}" method="POST">
            @csrf
            <div style="margin-bottom:10px;">
                <label class="detail-label">Note (optional)</label>
                <textarea name="admin_note" placeholder="Add a note..." rows="3"
                    style="width:100%;margin-top:4px;padding:10px;border:1px solid var(--border);border-radius:8px;font-size:13px;font-family:inherit;resize:none;outline:none;"></textarea>
            </div>
            <button type="submit" class="btn-action btn-green" style="width:100%;justify-content:center;">
                <i class="ti ti-check"></i> Approve & Forward to Store
            </button>
        </form>

        <form action="{{ route('admin.messages.reject', $message) }}" method="POST">
            @csrf
            <div style="margin-bottom:10px;">
                <label class="detail-label">Rejection reason (optional)</label>
                <textarea name="admin_note" placeholder="Reason for rejection..." rows="3"
                    style="width:100%;margin-top:4px;padding:10px;border:1px solid var(--border);border-radius:8px;font-size:13px;font-family:inherit;resize:none;outline:none;"></textarea>
            </div>
            <button type="submit" class="btn-action btn-red" style="width:100%;justify-content:center;">
                <i class="ti ti-x"></i> Reject Message
            </button>
        </form>
    </div>
</div>
@endif

{{-- Store reply if exists --}}
@if($message->store_reply)
<div class="detail-card">
    <div style="font-size:14px;font-weight:700;color:var(--dark);margin-bottom:12px;">Store Reply</div>
    <div class="reply-box">
        <div style="font-size:12px;color:#15803d;font-weight:600;margin-bottom:8px;">
            <i class="ti ti-check"></i>
            Replied on {{ $message->replied_at?->format('M d, Y H:i') }}
        </div>
        <div style="font-size:13px;color:#166534;white-space:pre-wrap;">{{ $message->store_reply }}</div>
    </div>
</div>
@endif

</x-admin-layout>
