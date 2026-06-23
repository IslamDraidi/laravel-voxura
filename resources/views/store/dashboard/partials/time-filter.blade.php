{{-- Variables: $title, $subtitle (optional), $range, $section --}}

<div class="time-filter-bar">
    <div class="time-filter-left">
        <h1 class="page-title">{{ $title }}</h1>
        @if(!empty($subtitle))
        <p class="page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="time-filter-right">
        <div class="time-filter-tabs">
            @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['range' => $key]) }}"
               class="time-tab {{ $range === $key ? 'time-tab-active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
        <a href="{{ route('store.dashboard.print', [$section ?? 'overview', 'range' => $range]) }}"
           target="_blank" class="btn-print">🖨️ Print</a>
    </div>
</div>
