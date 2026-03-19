<x-layout>
    <x-slot:title>Episodes</x-slot:title>

    <div class="mb-10 flex flex-col sm:flex-row justify-between items-center gap-4 relative z-10">
        <div>
            <h2 class="text-4xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-lg tracking-widest">Episodes</h2>
            <p class="text-lg font-bold text-fuchsia-300 mt-1 uppercase tracking-wider">All recorded episodes of the series.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-purple-900 p-6 jojo-border jojo-shadow mb-10 relative z-10">
        <form action="{{ route('episodes.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="SEARCH EPISODES..." class="block w-full px-4 py-3 bg-fuchsia-100 jojo-border focus:ring-4 focus:ring-yellow-400 text-purple-900 font-bold uppercase placeholder-purple-400/70">
            </div>
            <div class="w-full sm:w-64">
                <select name="part_id" class="block w-full px-4 py-3 bg-fuchsia-100 jojo-border focus:ring-4 focus:ring-yellow-400 text-purple-900 font-bold uppercase">
                    <option value="">ALL PARTS</option>
                    @foreach($parts as $part)
                        <option value="{{ $part->id }}" {{ request('part_id') == $part->id ? 'selected' : '' }}>
                            PART {{ $part->number }}: {{ strtoupper($part->title) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <x-form.button color="jojo">FILTER</x-form.button>
            <a href="{{ route('episodes.index') }}" class="inline-flex items-center justify-center px-6 py-2 bg-white text-purple-900 bangers text-xl tracking-widest jojo-border jojo-shadow hover:bg-slate-200 transform hover:-translate-y-1 hover:translate-x-1 transition-transform uppercase">CLEAR</a>
        </form>
    </div>

    <div class="bg-white text-purple-900 jojo-border jojo-shadow relative z-10">
        <x-table.table>
            <x-slot name="thead">
                <x-table.th>Episode</x-table.th>
                <x-table.th>Part</x-table.th>
                <x-table.sortable-th field="release_date" :current-sort="$sort" :current-direction="$direction">Air Date</x-table.sortable-th>
                <x-table.sortable-th field="imdb_score" :current-sort="$sort" :current-direction="$direction">IMDB Score</x-table.sortable-th>
                <x-table.sortable-th field="ratings_avg_rating" :current-sort="$sort" :current-direction="$direction">User Score</x-table.sortable-th>
                <x-table.th></x-table.th>
            </x-slot>

            @forelse($episodes as $episode)
                <tr class="border-t-4 border-slate-900 hover:bg-yellow-100 transition-colors group">
                    <x-table.td>
                        <div class="flex items-center">
                            @if($episode->thumbnail)
                                <a href="{{ route('episodes.show', $episode) }}" class="block shrink-0 mr-4">
                                    <img src="{{ $episode->thumbnail }}" 
                                         class="w-24 h-14 object-cover border-2 border-slate-900 shadow-[2px_2px_0px_#111] hover:scale-105 transition-transform">
                                </a>
                            @endif
                            <div class="bg-purple-900 text-yellow-400 font-black px-3 py-1 mr-4 border-2 border-slate-900 shadow-[2px_2px_0px_#111]">
                                #{{ $episode->episode_number }}
                            </div>
                            <div>
                                <div class="font-black text-2xl uppercase tracking-wider text-purple-900" title="{{ $episode->title }}">{{ Str::limit($episode->title, 25) }}</div>
                                <div class="text-xs font-bold text-slate-500 uppercase">{{ Str::limit($episode->summary, 40) }}</div>
                            </div>
                        </div>
                    </x-table.td>
                    <x-table.td>
                        <a href="{{ route('parts.show', $episode->part) }}" class="inline-block bg-fuchsia-100 text-fuchsia-800 font-black px-3 py-1 border-2 border-fuchsia-300 uppercase text-xs hover:bg-fuchsia-200 transition-colors">PART {{ $episode->part->number }}</a>
                    </x-table.td>
                    <x-table.td>
                        <span class="font-bold text-slate-600 uppercase">{{ \Carbon\Carbon::parse($episode->release_date)->format('M j, Y') }}</span>
                    </x-table.td>
                    <x-table.td>
                        <div class="flex items-center font-black text-xl text-yellow-600">
                            ★ {{ number_format($episode->imdb_score, 1) ?? 'N/A' }}
                        </div>
                    </x-table.td>
                    <x-table.td>
                        <div class="flex items-center font-black text-xl text-fuchsia-600">
                            ♥ {{ $episode->ratings_avg_rating ? number_format($episode->ratings_avg_rating, 1) : 'N/A' }}
                        </div>
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-end">
                            <a href="{{ route('episodes.show', $episode) }}" class="bg-indigo-600 text-white font-black px-4 py-2 border-2 border-slate-900 text-sm uppercase tracking-widest hover:bg-indigo-500 shadow-[2px_2px_0px_#111] transition-transform group-hover:-translate-y-1">VIEW</a>
                        </div>
                    </x-table.td>
                </tr>
            @empty
                <tr>
                    <x-table.td colspan="6" class="text-center py-20 bg-purple-900 text-yellow-400">
                        <div class="text-6xl bangers mb-4 transform -skew-x-6">NO RESULTS</div>
                        <p class="text-2xl font-bold uppercase tracking-widest">No episodes found.</p>
                    </x-table.td>
                </tr>
            @endforelse
        </x-table.table>
    </div>

    <div class="mt-8">
        {{ $episodes->links() }}
    </div>
</x-layout>
