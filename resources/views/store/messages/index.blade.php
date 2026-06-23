<x-layout title="Store Messages">

<style>
.store-msg-wrap{max-width:800px;margin:100px auto 60px;padding:0 24px;}
.store-msg-header{margin-bottom:28px;}
.store-msg-header h1{font-size:26px;font-weight:800;color:#1a1a1a;margin-bottom:6px;}
.store-msg-header p{font-size:14px;color:#666;}
.unread-badge{display:inline-flex;align-items:center;gap:6px;background:#fef3c7;color:#92400e;border-radius:20px;padding:5px 14px;font-size:13px;font-weight:600;margin-bottom:20px;}
.msg-card{background:#ffffff;border:1px solid #f0ede8;border-radius:14px;padding:22px 24px;margin-bottom:16px;}
.msg-card-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;}
.msg-sender{font-size:14px;font-weight:700;color:#1a1a1a;}
.msg-date{font-size:12px;color:#999;}
.msg-subject{font-size:13px;font-weight:600;color:#555;margin-bottom:8px;}
.msg-preview{font-size:13px;color:#666;line-height:1.6;margin-bottom:14px;background:#f8f7f5;border-radius:8px;padding:12px 14px;}
.status-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.status-waiting{background:#fef3c7;color:#92400e;}
.status-replied{background:#dcfce7;color:#15803d;}
.reply-form textarea{width:100%;padding:12px 14px;border:1.5px solid #e0ddd9;border-radius:10px;font-size:13px;font-family:inherit;color:#1a1a1a;background:#fff;outline:none;resize:none;margin-bottom:10px;}
.reply-form textarea:focus{border-color:var(--orange);}
.btn-reply{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:var(--orange);color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;}
.btn-reply:hover{background:var(--orange-dark);}
.replied-box{background:#f0fdf4;border:1px solid #16a34a;border-radius:10px;padding:14px 16px;margin-top:12px;}
.replied-box-header{font-size:12px;color:#15803d;font-weight:600;margin-bottom:8px;display:flex;align-items:center;gap:6px;}
.replied-box-text{font-size:13px;color:#166534;line-height:1.6;white-space:pre-wrap;}
.empty-state{text-align:center;padding:60px 0;color:#999;}
.empty-state i{font-size:48px;display:block;margin-bottom:12px;color:#ddd;}
</style>

<div class="store-msg-wrap">
    <div class="store-msg-header">
        <h1>Messages for {{ $store->name }}</h1>
        <p>Customer messages forwarded to you by Voxura.</p>
    </div>

    @if($unread > 0)
    <div class="unread-badge">
        <i class="ti ti-mail"></i>
        {{ $unread }} {{ $unread === 1 ? 'message needs' : 'messages need' }} your reply
    </div>
    @endif

    @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #16a34a;border-radius:10px;padding:14px 16px;color:#15803d;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
        <i class="ti ti-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    @if($messages->isEmpty())
    <div class="empty-state">
        <i class="ti ti-inbox"></i>
        <div style="font-size:16px;font-weight:600;color:#555;margin-bottom:8px;">No messages yet</div>
        <p style="font-size:13px;">When customers contact your store, approved messages will appear here.</p>
    </div>
    @else
        @foreach($messages as $msg)
        <div class="msg-card">
            <div class="msg-card-header">
                <div>
                    <div class="msg-sender">{{ $msg->customer_name }}</div>
                    <div class="msg-date">{{ $msg->created_at->format('M d, Y · H:i') }}</div>
                </div>
                @if($msg->store_reply)
                <span class="status-badge status-replied"><i class="ti ti-check"></i> Replied</span>
                @else
                <span class="status-badge status-waiting"><i class="ti ti-clock"></i> Awaiting reply</span>
                @endif
            </div>

            @if($msg->subject)
            <div class="msg-subject">Re: {{ $msg->subject }}</div>
            @endif

            <div class="msg-preview">{{ $msg->message }}</div>

            @if($msg->store_reply)
            <div class="replied-box">
                <div class="replied-box-header">
                    <i class="ti ti-check-circle"></i>
                    You replied on {{ $msg->replied_at?->format('M d, Y') }}:
                </div>
                <div class="replied-box-text">{{ $msg->store_reply }}</div>
            </div>
            @else
            <form class="reply-form" method="POST" action="{{ route('store.messages.reply', $msg) }}">
                @csrf
                <textarea name="store_reply" placeholder="Write your reply to {{ $msg->customer_name }}..." rows="4" required minlength="10" maxlength="2000"></textarea>
                <button type="submit" class="btn-reply">
                    <i class="ti ti-send"></i> Send Reply
                </button>
            </form>
            @endif
        </div>
        @endforeach

        <div style="margin-top:20px;">{{ $messages->links() }}</div>
    @endif
</div>

</x-layout>
