<x-layout>
    <x-slot:title>{{ $part->title }}</x-slot:title>

    <div class="mb-10 relative z-10">
        <a href="{{ route('parts.index') }}" class="inline-block bg-purple-900 text-yellow-400 font-black px-4 py-2 border-2 border-slate-900 mb-6 shadow-[2px_2px_0px_#111] hover:bg-purple-800 uppercase tracking-widest">
            ← BACK TO PARTS
        </a>
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="w-full md:w-1/3">
                <div class="bg-white p-4 jojo-border jojo-shadow transform -rotate-1">
                    @if($part->poster)
                        <img src="{{ $part->poster }}" class="w-full object-cover border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
                    @else
                        <div class="h-64 bg-purple-900 flex items-center justify-center text-6xl font-black text-yellow-400 opacity-20 border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
                            PART {{ $part->number }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex-1">
                <h2 class="text-6xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-xl tracking-widest leading-none mb-2">PART {{ $part->number }}: {{ strtoupper($part->title) }}</h2>
                <div class="flex items-center gap-4 mb-6">
                    <div class="inline-block bg-fuchsia-600 text-white font-black px-4 py-1 border-2 border-slate-900 uppercase tracking-widest shadow-[2px_2px_0px_#111]">
                        RELEASED IN {{ $part->release_year }}
                    </div>
                    @auth
                        <form action="{{ route('favorites.toggle', ['type' => 'part', 'id' => $part->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-white text-fuchsia-600 font-black px-4 py-1 border-2 border-slate-900 uppercase tracking-widest shadow-[2px_2px_0px_#111] hover:bg-fuchsia-50 transition-colors">
                                {{ $part->isFavoritedByAuthUser() ? '★ Favorited' : '☆ Add to Favorites' }}
                            </button>
                        </form>
                    @endauth
                </div>
                <p class="text-2xl font-bold text-white leading-relaxed drop-shadow-md bg-purple-900/50 p-6 jojo-border backdrop-blur-sm mb-8">{{ $part->summary }}</p>

                @if($part->trailer_url)
                    <div class="mt-8">
                        <h3 class="text-3xl bangers text-yellow-400 mb-4 uppercase tracking-widest border-b-4 border-fuchsia-600 inline-block">Official Trailer</h3>
                        <div class="bg-black jojo-border jojo-shadow aspect-video">
                            @php
                                $videoId = '';
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $part->trailer_url, $matches)) {
                                    $videoId = $matches[1];
                                }
                            @endphp
                            @if($videoId)
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white">
                                    <a href="{{ $part->trailer_url }}" target="_blank" class="text-yellow-400 underline bangers text-2xl tracking-widest">WATCH ON YOUTUBE</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-16 relative z-10">
        <h3 class="text-4xl text-yellow-400 bangers transform -skew-x-6 mb-8 tracking-widest">EPISODES</h3>
        
        <div class="space-y-6">
            @forelse($part->episodes as $episode)
                <div class="bg-white text-purple-900 jojo-border jojo-shadow p-6 flex flex-col md:flex-row items-center gap-6 hover:bg-yellow-50 transition-colors">
                    @if($episode->thumbnail)
                        <img src="{{ $episode->thumbnail }}" class="w-32 h-20 object-cover border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
                    @endif
                    <div class="bg-purple-900 text-yellow-400 bangers text-4xl px-6 py-2 border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
                        #{{ $episode->episode_number }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-3xl bangers uppercase tracking-widest leading-none mb-1" title="{{ $episode->title }}">{{ Str::limit($episode->title, 25) }}</h4>
                        <div class="flex items-center gap-4 text-sm font-black uppercase text-fuchsia-600">
                            <span>AIR DATE: {{ \Carbon\Carbon::parse($episode->release_date)->format('M j, Y') }}</span>
                            <span class="text-yellow-600">IMDB SCORE: ★ {{ $episode->imdb_score ?? 'N/A' }}</span>
                            @auth
                                @if($episode->isWatchedByAuthUser())
                                    <span class="bg-slate-900 text-white px-2 py-0.5 rounded shadow-[1px_1px_0px_#111]">✓ WATCHED</span>
                                @endif
                            @endauth
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('episodes.show', $episode) }}" class="bg-indigo-600 text-white font-black px-4 py-2 border-2 border-slate-900 text-sm uppercase tracking-widest hover:bg-indigo-500 shadow-[2px_2px_0px_#111]">VIEW</a>
                    </div>
                </div>
            @empty
                <div class="bg-purple-900 text-yellow-400 p-10 jojo-border jojo-shadow text-center">
                    <p class="text-2xl font-bold uppercase tracking-widest">No episodes have been recorded yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
