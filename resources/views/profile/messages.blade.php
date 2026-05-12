<x-layout title="{{ __('general.my_messages') }}">

<style>
.msg-page { max-width: 860px; margin: 4rem auto; padding: 2rem 1.5rem; }

.msg-heading {
    font-family: 'Playfair Display', serif;
    font-size: 2.25rem; font-weight: 800;
    color: var(--gray-900); margin-bottom: 0.4rem;
}
.msg-heading .accent { color: var(--orange); }
.msg-sub { color: #6B6B6B; font-size: 0.95rem; margin-bottom: 2.5rem; }

.msg-card {
    background: #fff;
    border: 1px solid #E8E0D8;
    border-radius: 16px;
    padding: 1.5rem 1.75rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: box-shadow 0.18s;
}
.msg-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); }

.msg-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.9rem;
    flex-wrap: wrap;
}
.msg-date {
    font-size: 0.78rem;
    color: #9CA3AF;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.msg-badge {
    font-size: 0.72rem;
    font-weight: 600;
    background: var(--orange-light);
    color: var(--orange);
    padding: 0.2rem 0.65rem;
    border-radius: 999px;
}
.msg-body {
    font-size: 0.95rem;
    color: #374151;
    line-height: 1.7;
    white-space: pre-wrap;
    word-break: break-word;
}

.msg-empty {
    text-align: center;
    padding: 4rem 2rem;
    color: #9CA3AF;
}
.msg-empty svg { margin: 0 auto 1rem; display: block; }
.msg-empty p { font-size: 1rem; margin-bottom: 1.25rem; }
.msg-empty a {
    display: inline-block;
    background: var(--orange);
    color: #fff;
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: background 0.15s;
}
.msg-empty a:hover { background: var(--orange-dark); }

.msg-pagination { margin-top: 2rem; }
.msg-pagination .pagination { display: flex; gap: 0.4rem; list-style: none; padding: 0; }
.msg-pagination .page-link {
    padding: 0.4rem 0.85rem;
    border-radius: 8px;
    font-size: 0.85rem;
    color: var(--gray-700);
    text-decoration: none;
    border: 1px solid #E5E7EB;
    transition: background 0.12s, color 0.12s;
}
.msg-pagination .page-item.active .page-link {
    background: var(--orange); color: #fff; border-color: var(--orange);
}
.msg-pagination .page-link:hover { background: var(--orange-light); color: var(--orange); }

[dir="rtl"] .msg-meta { flex-direction: row-reverse; }
[dir="rtl"] .msg-body { text-align: right; }
</style>

<div class="msg-page">
    <h1 class="msg-heading">
        {{ __('general.my_messages') }}
        <span class="accent">.</span>
    </h1>
    <p class="msg-sub">{{ __('general.my_messages_sub') }}</p>

    @if($messages->isEmpty())
        <div class="msg-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" fill="none"
                 viewBox="0 0 24 24" stroke="#D1D5DB" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <p>{{ __('general.no_messages_yet') }}</p>
            <a href="{{ url('/#contact') }}">{{ __('general.send_first_message') }}</a>
        </div>
    @else
        @foreach($messages as $msg)
        <div class="msg-card">
            <div class="msg-meta">
                <span class="msg-badge">{{ __('general.contact_label') }}</span>
                <span class="msg-date">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $msg->created_at->format('d M Y, H:i') }}
                </span>
            </div>
            <div class="msg-body">{{ $msg->message }}</div>
        </div>
        @endforeach

        @if($messages->hasPages())
        <div class="msg-pagination">
            {{ $messages->links() }}
        </div>
        @endif
    @endif
</div>

</x-layout>
