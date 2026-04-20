@props(['active'])

@php
$classes = ($active ?? false)
            ? 'topbar-link topbar-link-active w-full justify-start'
            : 'topbar-link w-full justify-start';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
