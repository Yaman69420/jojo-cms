@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 bangers text-2xl tracking-widest text-yellow-400 bg-purple-800 jojo-border jojo-shadow transform -translate-y-1'
            : 'flex items-center px-4 py-3 bangers text-2xl tracking-widest text-fuchsia-300 hover:text-yellow-400 hover:bg-purple-800 hover:jojo-border transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
