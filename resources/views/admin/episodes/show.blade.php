<x-layout>
    <x-slot:title>{{ $episode->title }}</x-slot:title>

    <div class="mb-10 relative z-10">
        <a href="{{ route('admin.episodes.index') }}" class="inline-block bg-purple-900 text-yellow-400 font-black px-4 py-2 border-2 border-slate-900 mb-6 shadow-[2px_2px_0px_#111] hover:bg-purple-800 uppercase tracking-widest">
            ← BACK TO EPISODES
        </a>
        <div class="flex flex-col md:flex-row gap-12 items-start">
            <div class="w-full md:w-1/3">
                <div class="bg-white p-4 jojo-border jojo-shadow transform rotate-1">
                    @if($episode->thumbnail)
                        <img src="{{ $episode->thumbnail }}" class="w-full object-cover border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
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

                <div class="mt-12 flex justify-end">
                    <a href="{{ route('admin.episodes.edit', $episode) }}" class="bg-yellow-400 text-purple-900 font-black px-8 py-3 border-4 border-slate-900 text-xl uppercase tracking-widest hover:bg-yellow-300 shadow-[4px_4px_0px_#111] transition-transform hover:-translate-y-1">EDIT EPISODE</a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
