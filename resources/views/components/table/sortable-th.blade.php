@props(['field', 'currentSort', 'currentDirection'])

@php
    $isCurrent = $field === $currentSort;
    $nextDirection = $isCurrent && $currentDirection === 'asc' ? 'desc' : 'asc';
    $icon = $isCurrent ? ($currentDirection === 'asc' ? '↑' : '↓') : '';
@endphp

<th scope="col" class="py-4 pl-4 pr-3 text-left text-xl font-bold bangers tracking-widest text-purple-900 sm:pl-6 uppercase transform -skew-x-6 border-b-4 border-slate-900 drop-shadow-sm group">
    <a href="{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $nextDirection]) }}" class="flex items-center hover:text-indigo-600 transition-colors">
        {{ $slot }}
        <span class="ml-2 font-sans">{{ $icon }}</span>
    </a>
</th>
