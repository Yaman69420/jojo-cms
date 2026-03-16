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
        <!-- Highlights / Hype Section -->
        <div class="lg:col-span-2">
            <h3 class="text-4xl text-yellow-400 bangers transform -skew-x-6 mb-8 tracking-widest bg-fuchsia-600 inline-block px-6 py-2 jojo-border shadow-[4px_4px_0px_#111]">BREAKING NEWS: PART 7 ARRIVES!</h3>
            
            <div class="relative bg-white text-purple-900 jojo-border jojo-shadow overflow-hidden group flex flex-col md:flex-row min-h-[500px]">
                <!-- Poster Side -->
                <div class="w-full md:w-72 bg-purple-800 relative border-b-4 md:border-b-0 md:border-r-4 border-slate-900 overflow-hidden">
                    <img src="{{ asset('storage/posters/sbr_poster.png') }}" 
                         onerror="this.style.display='none'"
                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 z-20">
                        <span class="inline-block bg-yellow-400 text-purple-900 bangers text-xl px-4 py-1 jojo-border animate-bounce">COMING THIS WEEK</span>
                    </div>
                </div>
                
                <!-- Info Side -->
                <div class="flex-1 flex flex-col">
                    <div class="p-8 bg-gradient-to-br from-white to-purple-50 flex-1">
                        <h4 class="text-7xl text-purple-900 bangers uppercase tracking-tighter leading-none mb-2">STEEL BALL RUN</h4>
                        <p class="text-fuchsia-600 font-bold text-xl uppercase tracking-widest mb-6">The race of a lifetime begins.</p>
                        
                        <p class="text-slate-700 font-bold leading-relaxed mb-10">
                            Prepare yourself for the most ambitious Part yet. Join Johnny Joestar and Gyro Zeppeli as they race across the American frontier in a 6,000km quest for glory and the ultimate prize.
                        </p>
                        
                        <div class="flex gap-4 max-w-sm">
                            <div class="bg-white p-4 jojo-border text-center flex-1 shadow-[2px_2px_0px_#111]">
                                <span class="block text-4xl bangers text-purple-900 leading-none">4</span>
                                <span class="block text-xs font-black text-slate-400 uppercase mt-1">DAYS TO GO</span>
                            </div>
                            <div class="bg-white p-4 jojo-border text-center flex-1 shadow-[2px_2px_0px_#111]">
                                <span class="block text-4xl bangers text-purple-900 leading-none">12</span>
                                <span class="block text-xs font-black text-slate-400 uppercase mt-1">HOURS</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-slate-900 flex items-center justify-between">
                        <span class="hidden md:inline-block text-fuchsia-400 font-black uppercase tracking-widest text-sm">March 19th Premiere</span>
                        <a href="https://www.youtube.com/watch?v=b51C8AbRDGU" target="_blank" class="bg-yellow-400 text-purple-900 bangers text-3xl px-10 py-4 jojo-border jojo-shadow hover:bg-yellow-300 transition-colors uppercase tracking-widest transform hover:-rotate-1 no-underline">
                            WATCH TRAILER
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Episode -->
        <div class="lg:col-span-1">
            <h3 class="text-4xl text-yellow-400 bangers transform -skew-x-6 mb-8 tracking-widest bg-slate-900 inline-block px-6 py-2 jojo-border shadow-[4px_4px_0px_#111]">LATEST EPISODE</h3>
            
            @if($latestEpisode)
            <div class="bg-white p-6 jojo-border jojo-shadow">
                <div class="mb-6 jojo-border aspect-video bg-purple-900 overflow-hidden relative">
                    @if($latestEpisode->thumbnail_url)
                        <img src="{{ $latestEpisode->thumbnail_url }}" class="w-full h-full object-cover">
                    @elseif($latestEpisode->media->first())
                        <img src="{{ asset('storage/' . $latestEpisode->media->first()->path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-8xl bangers text-yellow-400 opacity-20">EP{{ $latestEpisode->episode_number }}</div>
                    @endif
                    <div class="absolute top-4 left-4 bg-yellow-400 text-purple-900 bangers text-2xl px-4 py-2 jojo-border">
                        EP #{{ $latestEpisode->episode_number }}
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-xs font-black text-fuchsia-600 uppercase tracking-widest">PART {{ $latestEpisode->part->number }}: {{ $latestEpisode->part->title }}</span>
                </div>
                <h4 class="text-4xl bangers text-purple-900 uppercase tracking-widest mb-4 leading-tight">{{ $latestEpisode->title }}</h4>
                <p class="text-slate-600 font-bold mb-8 leading-relaxed">
                    {{ Str::limit($latestEpisode->summary, 120) }}
                </p>
                <a href="{{ route('episodes.show', $latestEpisode) }}" class="inline-block w-full text-center bg-indigo-600 text-white bangers text-2xl py-4 jojo-border jojo-shadow hover:bg-indigo-500 transform hover:-translate-y-1 transition-transform uppercase tracking-widest no-underline">
                    VIEW EPISODE
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layout>
