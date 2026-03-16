<x-layout>
    <x-slot:title>My Profile</x-slot:title>

    <div class="mb-10 relative z-10">
        <div class="flex flex-col md:flex-row gap-8 items-center bg-purple-900 p-8 jojo-border jojo-shadow">
            <div class="w-32 h-32 bg-yellow-400 rounded-full flex items-center justify-center border-4 border-slate-900 shadow-[4px_4px_0px_#111]">
                <span class="text-6xl bangers text-purple-900">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-5xl text-yellow-400 bangers transform -skew-x-6 tracking-widest drop-shadow-lg">{{ strtoupper($user->name) }}</h2>
                <p class="text-xl font-bold text-fuchsia-300 uppercase tracking-widest">{{ $user->email }}</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                    <div class="bg-white/10 px-4 py-2 jojo-border backdrop-blur-sm">
                        <span class="block text-xs font-black text-fuchsia-400 uppercase mb-1">MEMBER SINCE</span>
                        <span class="text-xl font-black text-white uppercase tracking-widest">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="bg-white/10 px-4 py-2 jojo-border backdrop-blur-sm">
                        <span class="block text-xs font-black text-fuchsia-400 uppercase mb-1">EPISODES WATCHED</span>
                        <span class="text-xl font-black text-white uppercase tracking-widest">{{ $user->watchedEpisodes->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 relative z-10 mb-20">
        <!-- Favorites -->
        <div>
            <h3 class="text-4xl text-yellow-400 bangers transform -skew-x-6 mb-8 tracking-widest bg-fuchsia-600 inline-block px-6 py-2 jojo-border shadow-[4px_4px_0px_#111]">FAVORITES</h3>
            
            <div class="space-y-4">
                @forelse($user->favorites as $favorite)
                    <div class="bg-white p-4 jojo-border jojo-shadow flex justify-between items-center group hover:bg-yellow-50 transition-colors">
                        <div>
                            <span class="block text-xs font-black text-fuchsia-600 uppercase">{{ $favorite->favoritable_type === 'App\Models\Part' ? 'PART' : 'EPISODE' }}</span>
                            <span class="text-2xl bangers text-purple-900 uppercase tracking-widest">
                                {{ $favorite->favoritable->title }}
                            </span>
                        </div>
                        <a href="{{ $favorite->favoritable_type === 'App\Models\Part' ? route('parts.show', $favorite->favoritable) : route('episodes.show', $favorite->favoritable) }}" 
                           class="bg-indigo-600 text-white font-black px-4 py-2 border-2 border-slate-900 text-xs uppercase tracking-widest hover:bg-indigo-500 shadow-[2px_2px_0px_#111]">
                            VIEW
                        </a>
                    </div>
                @empty
                    <div class="bg-purple-900/50 p-10 jojo-border text-center">
                        <p class="font-bold text-white uppercase tracking-widest">You haven't favorited anything yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Watch History -->
        <div>
            <h3 class="text-4xl text-yellow-400 bangers transform -skew-x-6 mb-8 tracking-widest bg-slate-900 inline-block px-6 py-2 jojo-border shadow-[4px_4px_0px_#111]">WATCH HISTORY</h3>
            
            <div class="space-y-4">
                @forelse($user->watchedEpisodes->take(10) as $episode)
                    <div class="bg-white p-4 jojo-border jojo-shadow flex justify-between items-center group hover:bg-yellow-50 transition-colors">
                        <div>
                            <span class="block text-xs font-black text-slate-500 uppercase">EPISODE #{{ $episode->episode_number }}</span>
                            <span class="text-2xl bangers text-purple-900 uppercase tracking-widest">{{ $episode->title }}</span>
                        </div>
                        <a href="{{ route('episodes.show', $episode) }}" 
                           class="bg-indigo-600 text-white font-black px-4 py-2 border-2 border-slate-900 text-xs uppercase tracking-widest hover:bg-indigo-500 shadow-[2px_2px_0px_#111]">
                            VIEW
                        </a>
                    </div>
                @empty
                    <div class="bg-purple-900/50 p-10 jojo-border text-center">
                        <p class="font-bold text-white uppercase tracking-widest">You haven't watched any episodes yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>
