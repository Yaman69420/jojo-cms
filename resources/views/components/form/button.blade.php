@props(['type' => 'submit', 'color' => 'jojo'])

@php
$colors = [
    'jojo' => 'bg-fuchsia-600 text-yellow-300 hover:bg-fuchsia-500 jojo-shadow',
    'yellow' => 'bg-yellow-400 text-purple-900 hover:bg-yellow-300 jojo-shadow',
    'red' => 'bg-red-600 text-white hover:bg-red-500 jojo-shadow',
    'white' => 'bg-white text-slate-900 hover:bg-slate-100 jojo-shadow',
];
$colorClasses = $colors[$color] ?? $colors['jojo'];
@endphp

<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center px-6 py-2 jojo-border text-xl font-bold uppercase tracking-widest bangers transform hover:-translate-y-1 hover:translate-x-1 transition-transform focus:outline-none focus:ring-4 focus:ring-yellow-400 $colorClasses"]) }}
>
    {{ $slot }}
</button>
