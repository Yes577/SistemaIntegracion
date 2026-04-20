@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1'])

@php
$alignmentClasses = match ($align) {
    'left' => 'origin-top-left start-0',
    'top' => 'origin-top',
    default => 'origin-top-right end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 rounded-[1.3rem] border border-cyan-400/15 bg-slate-950/95 shadow-2xl backdrop-blur-xl {{ $width }} {{ $alignmentClasses }}"
        style="display: none;"
        @click="open = false"
    >
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
