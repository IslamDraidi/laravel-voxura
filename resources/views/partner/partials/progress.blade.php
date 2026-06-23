@php
  $steps = [
    1 => ['label' => __('general.application'), 'route' => 'partner.apply'],
    2 => ['label' => __('general.choose_plan'),  'route' => 'partner.plan'],
    3 => ['label' => __('general.payment'),       'route' => 'partner.payment'],
  ];
@endphp

<div class="partner-progress">
  @foreach($steps as $num => $step)
    <div class="progress-step {{ $currentStep >= $num ? 'progress-step-done' : '' }} {{ $currentStep === $num ? 'progress-step-active' : '' }}">
      <div class="progress-step-circle">
        @if($currentStep > $num)
          <i class="ti ti-check" aria-hidden="true"></i>
        @else
          {{ $num }}
        @endif
      </div>
      <span class="progress-step-label">{{ $step['label'] }}</span>
    </div>
    @if(!$loop->last)
      <div class="progress-connector {{ $currentStep > $num ? 'progress-connector-done' : '' }}"></div>
    @endif
  @endforeach
</div>

<style>
.partner-progress {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0;
  margin-bottom: 48px;
}
.progress-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}
.progress-step-circle {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: #f0ede8;
  border: 2px solid #e0ddd9;
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; font-weight: 700;
  color: #aaa;
  transition: all 0.3s;
}
.progress-step-active .progress-step-circle {
  background: var(--orange);
  border-color: var(--orange);
  color: #fff;
}
.progress-step-done .progress-step-circle {
  background: #1D9E75;
  border-color: #1D9E75;
  color: #fff;
}
.progress-step-label {
  font-size: 12px; font-weight: 600;
  color: #aaa;
  white-space: nowrap;
}
.progress-step-active .progress-step-label { color: var(--orange); }
.progress-step-done .progress-step-label   { color: #1D9E75; }
.progress-connector {
  flex: 1;
  min-width: 48px;
  height: 2px;
  background: #e0ddd9;
  margin: 0 8px;
  margin-bottom: 24px;
  transition: background 0.3s;
}
.progress-connector-done { background: #1D9E75; }
</style>
