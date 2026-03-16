<x-layout>
    <x-slot:title>{{ $episode->title }}</x-slot:title>

    <div class="mb-10 relative z-10">
        <a href="{{ route('episodes.index') }}" class="inline-block bg-purple-900 text-yellow-400 font-black px-4 py-2 border-2 border-slate-900 mb-6 shadow-[2px_2px_0px_#111] hover:bg-purple-800 uppercase tracking-widest">
            ← BACK TO EPISODES
        </a>
        <div class="flex flex-col md:flex-row gap-12 items-start">
            <div class="w-full md:w-1/3">
                <div class="bg-white p-4 jojo-border jojo-shadow transform rotate-1">
                    @if($episode->thumbnail_url)
                        <img src="{{ $episode->thumbnail_url }}" class="w-full object-cover border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
                    @elseif($episode->media->first())
                        <img src="{{ asset('storage/' . $episode->media->first()->path) }}" class="w-full object-cover border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
                    @else
                        <div class="h-64 bg-purple-900 flex items-center justify-center text-6xl font-black text-yellow-400 opacity-20 border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
                            #{{ $episode->episode_number }}
                        </div>
                    @endif
                </div>

                <div class="mt-8 space-y-4">
                    <div class="bg-white p-6 jojo-border jojo-shadow">
                        <span class="block text-xs font-black text-slate-500 uppercase mb-1">RELEASED ON</span>
                        <span class="text-2xl font-black text-purple-900 uppercase tracking-widest">{{ \Carbon\Carbon::parse($episode->release_date)->format('F j, Y') }}</span>
                    </div>

                    <div class="bg-fuchsia-100 p-6 jojo-border jojo-shadow border-fuchsia-400">
                        <span class="block text-xs font-black text-fuchsia-500 uppercase mb-1">IMDB SCORE</span>
                        <span class="text-4xl font-black text-fuchsia-600 uppercase tracking-widest">★ {{ $episode->imdb_score ?? 'N/A' }}</span>
                    </div>

                    @auth
                    <div class="bg-yellow-100 p-6 jojo-border jojo-shadow border-yellow-400">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-2xl bangers text-purple-900 uppercase tracking-widest">Rate this Episode</h4>
                            @if($episode->authUserRating())
                                <form action="{{ route('episodes.rate.delete', $episode) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-black text-red-600 uppercase hover:underline">Remove Rating</button>
                                </form>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @for ($i = 1; $i <= 10; $i++)
                                <form action="{{ route('episodes.rate', $episode) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="rating" value="{{ $i }}">
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center border-2 border-slate-900 font-black text-lg transition-all hover:scale-110 hover:shadow-[2px_2px_0px_#111] {{ ($episode->authUserRating() ?? 0) >= $i ? 'bg-yellow-400 text-purple-900' : 'bg-white text-slate-400' }}">
                                        {{ $i }}
                                    </button>
                                </form>
                            @endfor
                        </div>
                        @if($episode->authUserRating())
                            <p class="mt-4 text-sm font-bold text-purple-900 uppercase tracking-widest">Your Rating: {{ $episode->authUserRating() }}/10</p>
                        @endif
                    </div>

                    <div class="flex gap-4">
                        <form action="{{ route('episodes.toggle-watched', $episode) }}" method="POST" class="flex-1">
                            @csrf
                            <x-form.button color="jojo" class="w-full py-4 text-xl">
                                {{ $episode->isWatchedByAuthUser() ? '✓ Watched' : 'Mark as Watched' }}
                            </x-form.button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>

            <div class="flex-1">
                <div class="inline-block bg-purple-900 text-yellow-400 font-black px-4 py-1 border-2 border-slate-900 mb-4 uppercase tracking-widest shadow-[2px_2px_0px_#111]">
                    PART {{ $episode->part->number }}: {{ strtoupper($episode->part->title) }}
                </div>
                <h2 class="text-7xl text-white bangers transform -skew-x-6 drop-shadow-xl tracking-widest leading-none mb-8">#{{ $episode->episode_number }}: {{ strtoupper($episode->title) }}</h2>
                
                <div class="relative">
                    <div class="absolute -inset-4 bg-fuchsia-600/20 jojo-border -rotate-1"></div>
                    <div class="relative bg-white p-8 jojo-border jojo-shadow">
                        <h3 class="text-3xl bangers text-purple-900 mb-4 uppercase tracking-widest border-b-4 border-fuchsia-200 inline-block">SUMMARY</h3>
                        <p class="text-xl font-bold text-slate-700 leading-relaxed">{{ $episode->summary }}</p>
                    </div>
                </div>

                @auth
                <div class="mt-12 flex justify-end">
                    <form action="{{ route('favorites.toggle', ['type' => 'episode', 'id' => $episode->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-fuchsia-600 text-white font-black px-8 py-3 border-4 border-slate-900 text-xl uppercase tracking-widest hover:bg-fuchsia-500 shadow-[4px_4px_0px_#111] transition-transform hover:-translate-y-1">
                            {{ $episode->isFavoritedByAuthUser() ? '★ Favorited' : '☆ Add to Favorites' }}
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </div>
</x-layout>
