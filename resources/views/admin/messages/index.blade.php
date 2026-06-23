<x-admin-layout title="Messages" section="messages" active="messages">

<style>
.tab-count { display:inline-flex; align-items:center; justify-content:center; min-width:18px; height:18px; background:#E8621A; color:#fff; border-radius:10px; font-size:10px; font-weight:700; padding:0 5px; margin-left:6px; }
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
.stat-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:18px 20px;display:flex;flex-direction:column;gap:4px;}
.stat-icon{font-size:20px;margin-bottom:4px;}
.stat-num{font-size:26px;font-weight:800;color:var(--dark);}
.stat-label{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;}
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;}
.page-title{font-size:22px;font-weight:800;color:var(--dark);}
.page-sub{font-size:13px;color:var(--muted);margin-top:4px;}
.tab-bar{display:flex;gap:4px;margin-bottom:20px;border-bottom:1px solid var(--border);}
.tab-btn{padding:10px 18px;font-size:13px;font-weight:600;border:none;background:none;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px;text-decoration:none;display:inline-flex;align-items:center;}
.tab-btn.active{color:var(--orange);border-bottom-color:var(--orange);}
.tab-btn:hover:not(.active){color:var(--dark);}
.msg-table{width:100%;border-collapse:collapse;}
.msg-table th{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);padding:10px 14px;text-align:left;border-bottom:1px solid var(--border);}
.msg-table td{padding:12px 14px;font-size:13px;border-bottom:1px solid var(--gray-100);vertical-align:middle;}
.msg-table tr:hover td{background:var(--gray-50);}
.msg-table tr.tr-flagged td{background:#fff8f8;}
.msg-table tr.tr-flagged:hover td{background:#fff0f0;}
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-warning{background:#fef3c7;color:#92400e;}
.badge-danger{background:#fee2e2;color:#991b1b;}
.badge-success{background:#dcfce7;color:#15803d;}
.badge-secondary{background:#f3f4f6;color:#374151;}
.badge-info{background:#dbeafe;color:#1d4ed8;}
.flag-pill{display:inline-block;background:#fee2e2;color:#991b1b;border-radius:20px;padding:2px 8px;font-size:10px;font-weight:600;margin:1px;}
.td-link{color:var(--orange);text-decoration:none;font-weight:600;}
.td-link:hover{text-decoration:underline;}
.td-primary{font-weight:600;color:var(--dark);}
.td-secondary{font-size:11px;color:var(--muted);margin-top:2px;}
.td-truncate{max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.td-empty{padding:40px;text-align:center;color:var(--muted);font-size:13px;}
.td-actions{white-space:nowrap;}
.btn-action{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:none;cursor:pointer;text-decoration:none;font-size:14px;transition:all .15s;}
.btn-action-ghost{background:var(--gray-100);color:var(--gray-600);}
.btn-action-ghost:hover{background:var(--gray-200);color:var(--dark);}
.btn-action-success{background:#dcfce7;color:#15803d;}
.btn-action-success:hover{background:#bbf7d0;}
.btn-action-danger{background:#fee2e2;color:#991b1b;}
.btn-action-danger:hover{background:#fecaca;}
.action-form{display:inline;}
.table-wrap{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;}
.admin-pagination{padding:14px 16px;border-top:1px solid var(--border);}
.admin-page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;}
.admin-page-title{font-size:22px;font-weight:800;color:var(--dark);}
.admin-page-sub{font-size:13px;color:var(--muted);margin-top:4px;}
</style>

{{-- Page Header --}}
<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Messages</h1>
    <p class="admin-page-sub">Review and manage customer messages to stores</p>
  </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="ti ti-clock" style="color:var(--amber);"></i></div>
        <span class="stat-num" style="color:var(--amber);">{{ $stats['pending'] }}</span>
        <span class="stat-label">Needs Review</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ti ti-flag" style="color:var(--red);"></i></div>
        <span class="stat-num" style="color:var(--red);">{{ $stats['flagged'] }}</span>
        <span class="stat-label">Flagged</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ti ti-circle-check" style="color:var(--green);"></i></div>
        <span class="stat-num" style="color:var(--green);">{{ $stats['approved'] }}</span>
        <span class="stat-label">Approved</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ti ti-calendar" style="color:var(--blue);"></i></div>
        <span class="stat-num" style="color:var(--blue);">{{ $stats['today'] }}</span>
        <span class="stat-label">Received Today</span>
    </div>
</div>

{{-- Tabs --}}
@php $activeTab = request('tab', 'review'); $reviewCount = $stats['pending'] + $stats['flagged']; @endphp
<div class="tab-bar">
    <a href="{{ route('admin.messages.index') }}"
       class="tab-btn {{ $activeTab !== 'all' ? 'active' : '' }}">
        Needs Review
        @if($reviewCount > 0)
            <span class="tab-count">{{ $reviewCount }}</span>
        @endif
    </a>
    <a href="{{ route('admin.messages.index', ['tab' => 'all']) }}"
       class="tab-btn {{ $activeTab === 'all' ? 'active' : '' }}">
        All Messages
    </a>
</div>

{{-- Messages Table --}}
<div class="table-wrap">
@if($activeTab === 'all')
    {{-- All Messages --}}
    @if($approved->isEmpty())
    <table class="msg-table">
        <tbody><tr><td colspan="8" class="td-empty">No messages found</td></tr></tbody>
    </table>
    @else
    <div style="overflow-x:auto;">
        <table class="msg-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Flags</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approved as $message)
                @php
                    $statusColors = ['pending'=>'badge-warning','flagged'=>'badge-danger','approved'=>'badge-success','rejected'=>'badge-secondary','replied'=>'badge-info'];
                @endphp
                <tr class="{{ $message->status === 'flagged' ? 'tr-flagged' : '' }}">
                    <td style="color:var(--muted);">{{ $message->id }}</td>
                    <td>
                        <a href="{{ route('admin.stores.show', $message->store) }}" class="td-link">
                            {{ $message->store->name ?? '—' }}
                        </a>
                    </td>
                    <td>
                        <div class="td-primary">{{ $message->customer_name }}</div>
                        <div class="td-secondary">{{ $message->customer_email }}</div>
                    </td>
                    <td>
                        <div class="td-truncate">{{ $message->subject ?? 'General inquiry' }}</div>
                        <div class="td-secondary">{{ Str::limit($message->message, 60) }}</div>
                    </td>
                    <td>
                        @if($message->auto_approved)
                            <span class="badge badge-success">Auto-approved</span>
                        @else
                            @foreach($message->filter_flags ?? [] as $flag)
                                <span class="badge badge-danger">{{ $flag }}</span>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $statusColors[$message->status] ?? 'badge-secondary' }}">{{ ucfirst($message->status) }}</span>
                    </td>
                    <td style="white-space:nowrap;">
                        <div>{{ $message->created_at->format('M d, Y') }}</div>
                        <div class="td-secondary">{{ $message->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="td-actions">
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.messages.show', $message) }}" class="btn-action btn-action-ghost" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            @if(in_array($message->status, ['pending','flagged']))
                            <form action="{{ route('admin.messages.approve', $message) }}" method="POST" class="action-form">
                                @csrf
                                <button type="submit" class="btn-action btn-action-success" title="Approve">
                                    <i class="ti ti-check"></i>
                                </button>
                            </form>
                            <button type="button" onclick="openRejectModal({{ $message->id }})" class="btn-action btn-action-danger" title="Reject">
                                <i class="ti ti-x"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $approved->appends(request()->query())->links() }}</div>
    @endif
@else
    {{-- Needs Review --}}
    @if($pending->isEmpty())
    <table class="msg-table">
        <tbody><tr><td colspan="8" class="td-empty">No messages need review</td></tr></tbody>
    </table>
    @else
    <div style="overflow-x:auto;">
        <table class="msg-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Flags</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $message)
                @php
                    $statusColors = ['pending'=>'badge-warning','flagged'=>'badge-danger','approved'=>'badge-success','rejected'=>'badge-secondary','replied'=>'badge-info'];
                @endphp
                <tr class="{{ $message->status === 'flagged' ? 'tr-flagged' : '' }}">
                    <td style="color:var(--muted);">{{ $message->id }}</td>
                    <td>
                        <a href="{{ route('admin.stores.show', $message->store) }}" class="td-link">
                            {{ $message->store->name ?? '—' }}
                        </a>
                    </td>
                    <td>
                        <div class="td-primary">{{ $message->customer_name }}</div>
                        <div class="td-secondary">{{ $message->customer_email }}</div>
                    </td>
                    <td>
                        <div class="td-truncate">{{ $message->subject ?? 'General inquiry' }}</div>
                        <div class="td-secondary">{{ Str::limit($message->message, 60) }}</div>
                    </td>
                    <td>
                        @if($message->auto_approved)
                            <span class="badge badge-success">Auto-approved</span>
                        @else
                            @foreach($message->filter_flags ?? [] as $flag)
                                <span class="badge badge-danger">{{ $flag }}</span>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $statusColors[$message->status] ?? 'badge-secondary' }}">{{ ucfirst($message->status) }}</span>
                    </td>
                    <td style="white-space:nowrap;">
                        <div>{{ $message->created_at->format('M d, Y') }}</div>
                        <div class="td-secondary">{{ $message->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="td-actions">
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.messages.show', $message) }}" class="btn-action btn-action-ghost" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <form action="{{ route('admin.messages.approve', $message) }}" method="POST" class="action-form">
                                @csrf
                                <button type="submit" class="btn-action btn-action-success" title="Approve">
                                    <i class="ti ti-check"></i>
                                </button>
                            </form>
                            <button type="button" onclick="openRejectModal({{ $message->id }})" class="btn-action btn-action-danger" title="Reject">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $pending->links() }}</div>
    @endif
@endif
</div>

{{-- Reject Modal --}}
<div id="reject-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:var(--white);border:1px solid var(--border);border-radius:14px;padding:28px;width:460px;">
    <h3 style="font-size:16px;font-weight:700;margin-bottom:16px;color:var(--dark);">Reject Message</h3>
    <form method="POST" id="reject-form" action="">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="font-size:12px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:6px;">Reason for rejection (optional)</label>
        <textarea name="admin_note" rows="3" placeholder="e.g. Contains spam keywords..." style="width:100%;background:var(--gray-50);border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:13px;color:var(--dark);font-family:inherit;resize:none;outline:none;box-sizing:border-box;"></textarea>
      </div>
      <div style="display:flex;gap:10px;">
        <button type="submit" style="flex:1;background:#E24B4A;color:#fff;border:none;border-radius:8px;padding:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;">Reject Message</button>
        <button type="button" onclick="closeRejectModal()" style="flex:1;background:transparent;color:var(--muted);border:1px solid var(--border);border-radius:8px;padding:10px;font-size:14px;cursor:pointer;font-family:inherit;">Cancel</button>
      </div>
    </form>
  </div>
</div>
<script>
function openRejectModal(messageId) {
  const form = document.getElementById('reject-form');
  form.action = '/admin/messages/' + messageId + '/reject';
  document.getElementById('reject-modal').style.display = 'flex';
}
function closeRejectModal() {
  document.getElementById('reject-modal').style.display = 'none';
}
</script>

</x-admin-layout>
