@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'status-banner-success']) }}>
        {{ $status }}
    </div>
@endif
