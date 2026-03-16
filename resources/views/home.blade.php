<x-layout>
    <x-slot:title>Home</x-slot:title>

    <!-- Hero Section -->
    <div class="relative mb-16 overflow-hidden bg-purple-900 jojo-border jojo-shadow p-8 lg:p-12 transform -rotate-1">
        <div class="relative z-10 flex flex-col lg:flex-row gap-12 items-center">
            <div class="flex-1 text-center lg:text-left">
                <h2 class="text-7xl lg:text-9xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-2xl leading-none mb-6 tracking-widest">
                    THE CHRONICLES
                </h2>
                <p class="text-2xl lg:text-3xl font-black text-fuchsia-300 uppercase tracking-widest mb-10 drop-shadow">
                    Explore the definitive history of the series.
                </p>
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="{{ route('parts.index') }}" class="bg-yellow-400 text-purple-900 bangers text-3xl px-10 py-4 jojo-border jojo-shadow hover:bg-yellow-300 transform hover:-translate-y-1 transition-transform uppercase tracking-widest">
                        BROWSE PARTS
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="bg-white text-purple-900 bangers text-3xl px-10 py-4 jojo-border jojo-shadow hover:bg-slate-200 transform hover:-translate-y-1 transition-transform uppercase tracking-widest">
                            JOIN THE FANDOM
                        </a>
                    @endguest
                </div>
            </div>

            @auth
            <!-- User Dashboard Side -->
            <div class="w-full lg:w-96 bg-white/10 backdrop-blur-md p-8 jojo-border border-white/20 shadow-2xl rotate-2">
                <h3 class="text-3xl bangers text-white uppercase tracking-widest mb-6 border-b-2 border-yellow-400 inline-block">COMMUNITY DASHBOARD</h3>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-fuchsia-300 uppercase tracking-widest">FAVORITES</span>
                        <span class="text-3xl bangers text-yellow-400">{{ $userActivity['favoritesCount'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-fuchsia-300 uppercase tracking-widest">YOUR RATINGS</span>
                        <span class="text-3xl bangers text-yellow-400">{{ $userActivity['ratingsCount'] }}</span>
                    </div>

                    @if($userActivity['lastWatched'])
                    <div class="mt-8 pt-6 border-t border-white/10">
                        <span class="block text-xs font-black text-yellow-400 uppercase tracking-widest mb-2">CONTINUE WATCHING:</span>
                        <a href="{{ route('episodes.show', $userActivity['lastWatched']) }}" class="block group">
                            <span class="block text-2xl bangers text-white uppercase group-hover:text-yellow-400 transition-colors leading-tight">
                                {{ $userActivity['lastWatched']->title }}
                            </span>
                            <span class="block text-sm font-bold text-fuchsia-300 uppercase tracking-widest mt-1">
                                PART {{ $userActivity['lastWatched']->part->number }}
                            </span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endauth
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 relative z-10">
        <div class="bg-white p-8 jojo-border jojo-shadow text-center group hover:bg-yellow-50 transition-colors">
            <span class="block text-sm font-black text-fuchsia-600 uppercase tracking-widest mb-2">Total Parts</span>
            <span class="block text-6xl bangers text-purple-900 transform -skew-x-6 group-hover:scale-110 transition-transform">{{ $stats['parts'] }}</span>
        </div>
        <div class="bg-white p-8 jojo-border jojo-shadow text-center group hover:bg-yellow-50 transition-colors">
            <span class="block text-sm font-black text-fuchsia-600 uppercase tracking-widest mb-2">Total Episodes</span>
            <span class="block text-6xl bangers text-purple-900 transform -skew-x-6 group-hover:scale-110 transition-transform">{{ $stats['episodes'] }}</span>
        </div>
        <div class="bg-white p-8 jojo-border jojo-shadow text-center group hover:bg-yellow-50 transition-colors">
            <span class="block text-sm font-black text-fuchsia-600 uppercase tracking-widest mb-2">Active Fans</span>
            <span class="block text-6xl bangers text-purple-900 transform -skew-x-6 group-hover:scale-110 transition-transform">{{ $stats['users'] }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 relative z-10">
        <!-- Highlights -->
        <div class="lg:col-span-2">
            <h3 class="text-4xl text-yellow-400 bangers transform -skew-x-6 mb-8 tracking-widest bg-fuchsia-600 inline-block px-6 py-2 jojo-border shadow-[4px_4px_0px_#111]">COMMUNITY HIGHLIGHTS</h3>
            
            <div class="space-y-6">
                @foreach($topEpisodes as $episode)
                <a href="{{ route('episodes.show', $episode) }}" class="flex flex-col md:flex-row bg-white text-purple-900 jojo-border jojo-shadow overflow-hidden hover:scale-[1.01] transition-transform group">
                    <div class="w-full md:w-48 bg-purple-900 flex items-center justify-center p-4 border-b-4 md:border-b-0 md:border-r-4 border-slate-900">
                        @if($episode->media->first())
                            <img src="{{ asset('storage/' . $episode->media->first()->path) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-5xl bangers text-yellow-400">#{{ $episode->episode_number }}</span>
                        @endif
                    </div>
                    <div class="p-6 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="block text-xs font-black text-fuchsia-600 uppercase tracking-widest">PART {{ $episode->part->number }}</span>
                                <h4 class="text-3xl bangers uppercase tracking-widest group-hover:text-indigo-600 transition-colors">{{ $episode->title }}</h4>
                            </div>
                            <div class="text-right">
                                <span class="block text-xl bangers text-yellow-600">♥ {{ number_format($episode->ratings_avg_rating, 1) }}</span>
                                <span class="block text-xs font-black text-slate-400 uppercase">USER AVG</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Latest Update -->
        <div class="lg:col-span-1">
            <h3 class="text-4xl text-yellow-400 bangers transform -skew-x-6 mb-8 tracking-widest bg-slate-900 inline-block px-6 py-2 jojo-border shadow-[4px_4px_0px_#111]">LATEST PART</h3>
            
            @if($latestPart)
            <div class="bg-white p-6 jojo-border jojo-shadow">
                <div class="mb-6 jojo-border aspect-square bg-purple-900 overflow-hidden relative">
                    @if($latestPart->media->first())
                        <img src="{{ asset('storage/' . $latestPart->media->first()->path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-8xl bangers text-yellow-400 opacity-20">P{{ $latestPart->number }}</div>
                    @endif
                    <div class="absolute top-4 left-4 bg-yellow-400 text-purple-900 bangers text-3xl px-4 py-2 jojo-border">
                        PART {{ $latestPart->number }}
                    </div>
                </div>
                <h4 class="text-4xl bangers text-purple-900 uppercase tracking-widest mb-4 leading-tight">{{ $latestPart->title }}</h4>
                <p class="text-slate-600 font-bold mb-8 leading-relaxed">
                    {{ Str::limit($latestPart->summary, 150) }}
                </p>
                <a href="{{ route('parts.show', $latestPart) }}" class="inline-block w-full text-center bg-indigo-600 text-white bangers text-2xl py-4 jojo-border jojo-shadow hover:bg-indigo-500 transform hover:-translate-y-1 transition-transform uppercase tracking-widest">
                    EXPLORE PART
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layout>
