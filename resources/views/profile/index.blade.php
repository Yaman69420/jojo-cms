<x-layout>
    <x-slot:title>{{ $searchTerm ? 'Searching for: ' . $searchTerm : 'User Sanctuary' }}</x-slot:title>

    <div class="mb-12 relative z-10">
        <div class="bg-purple-900 p-8 jojo-border jojo-shadow relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 text-white opacity-5 select-none pointer-events-none text-9xl bangers rotate-12">FANS</div>
            
            <h2 class="text-5xl text-yellow-400 bangers transform -skew-x-6 tracking-widest drop-shadow-lg mb-6">FIND OTHER JOJO FANS</h2>
            
            <form action="{{ route('profile.index') }}" method="GET" class="relative max-w-2xl">
                <input type="text" name="search" value="{{ $searchTerm }}" placeholder="Search by name or email..." 
                       class="w-full bg-white border-4 border-slate-900 p-4 bangers text-2xl text-purple-900 focus:outline-none focus:bg-yellow-50 transition-colors shadow-[4px_4px_0px_#111]">
                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-indigo-600 text-white px-6 bangers text-xl jojo-border hover:bg-indigo-500 transition-colors">
                    SEARCH
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative z-10 mb-20">
        @forelse($users as $user)
            <div class="bg-white jojo-border jojo-shadow overflow-hidden flex flex-col group hover:transform hover:-translate-y-1 transition-all">
                <div class="bg-purple-900 p-4 border-b-4 border-slate-900 flex items-center gap-4">
                    <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center border-2 border-slate-900 shadow-[2px_2px_0px_#111] overflow-hidden shrink-0">
                        @if($user->avatar_url)
                            <img src="{{ asset('storage/' . $user->avatar_url) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl bangers text-purple-900">{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 truncate">
                        <h3 class="text-2xl text-yellow-400 bangers truncate tracking-widest leading-none mb-1">{{ strtoupper($user->name) }}</h3>
                        <span class="text-xs font-black text-fuchsia-400 uppercase tracking-widest">{{ $user->followers_count }} Followers</span>
                    </div>
                </div>
                
                <div class="p-6 flex-1 flex flex-col justify-between gap-6">
                    <p class="text-slate-500 font-bold italic truncate">Joined {{ $user->created_at->format('M Y') }}</p>
                    
                    <div class="flex gap-4">
                        <a href="{{ route('profile.show', $user) }}" class="flex-1 bg-slate-900 text-white text-center bangers text-xl py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-slate-800 transition-colors uppercase tracking-widest no-underline">
                            View Soul
                        </a>
                        
                        @if(Auth::user()->isFollowing($user))
                            <form action="{{ route('profile.unfollow', $user) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-slate-200 text-slate-600 bangers text-xl py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-slate-300 transition-colors uppercase tracking-widest">
                                    Unfollow
                                </button>
                            </form>
                        @else
                            <form action="{{ route('profile.follow', $user) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-fuchsia-600 text-white bangers text-xl py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-fuchsia-500 transition-colors uppercase tracking-widest">
                                    Follow
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-purple-900/50 p-20 jojo-border text-center">
                <p class="text-3xl text-white bangers uppercase tracking-widest mb-4">No JoJo fans found matching your search.</p>
                @if($searchTerm)
                    <a href="{{ route('profile.index') }}" class="text-yellow-400 underline bangers text-xl uppercase tracking-widest hover:text-yellow-300">Clear Search</a>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mb-20">
        {{ $users->links() }}
    </div>
</x-layout>
