<x-layout>
    <x-slot:title>My Profile</x-slot:title>

    <div class="mb-10 relative z-10">
        <div class="flex flex-col md:flex-row gap-8 items-center bg-purple-900 p-8 jojo-border jojo-shadow overflow-hidden relative">
            <!-- Decorative Background Element -->
            <div class="absolute -right-10 -bottom-10 text-white opacity-5 select-none pointer-events-none text-9xl bangers rotate-12">JOJO</div>

            <!-- Avatar -->
            <div class="relative group">
                <div class="w-40 h-40 bg-yellow-400 rounded-full flex items-center justify-center border-4 border-slate-900 shadow-[6px_6px_0px_#111] overflow-hidden">
                    @if($user->avatar_url)
                        <img src="{{ asset('storage/' . $user->avatar_url) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-7xl bangers text-purple-900">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                @if(Auth::id() === $user->id)
                <button onclick="document.getElementById('edit-modal').classList.remove('hidden')" class="absolute bottom-0 right-0 bg-fuchsia-600 text-white p-2 border-2 border-slate-900 shadow-[2px_2px_0px_#111] hover:bg-fuchsia-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </button>
                @endif
            </div>

            <div class="flex-1 text-center md:text-left relative z-10">
                <div class="flex flex-col md:flex-row md:items-end gap-4 mb-4">
                    <h2 class="text-6xl text-yellow-400 bangers transform -skew-x-6 tracking-widest drop-shadow-lg leading-none">{{ strtoupper($user->name) }}</h2>
                    @auth
                        @if(Auth::id() !== $user->id)
                            @if(Auth::user()->isFriendsWith($user))
                                <div class="flex gap-2">
                                    <a href="{{ route('chat', $user->id) }}" class="bg-indigo-600 text-white bangers text-xl px-6 py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-indigo-500 transition-colors uppercase no-underline">Message</a>
                                    <form action="{{ route('friendships.unfriend', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-slate-800 text-fuchsia-400 bangers text-xl px-6 py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-slate-700 transition-colors uppercase">Unfriend</button>
                                    </form>
                                </div>
                            @elseif(Auth::user()->hasSentRequestTo($user))
                                <form action="{{ route('friendships.unfriend', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-slate-700 text-yellow-400 bangers text-xl px-6 py-2 jojo-border shadow-[2px_2px_0px_#111] cursor-default uppercase">Request Pending</button>
                                </form>
                            @elseif(Auth::user()->hasPendingRequestFrom($user))
                                <div class="flex gap-2">
                                    <form action="{{ route('friendships.accept', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white bangers text-xl px-6 py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-green-500 transition-colors uppercase">Accept</button>
                                    </form>
                                    <form action="{{ route('friendships.reject', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 text-white bangers text-xl px-6 py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-red-500 transition-colors uppercase">Reject</button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('friendships.send', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-fuchsia-600 text-white bangers text-xl px-6 py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-fuchsia-500 transition-colors uppercase">Add Friend</button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
                
                <p class="text-xl font-bold text-fuchsia-300 uppercase tracking-widest mb-6">{{ $user->email }}</p>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <div class="bg-white/10 px-6 py-3 jojo-border backdrop-blur-sm shadow-[4px_4px_0px_rgba(0,0,0,0.3)]">
                        <span class="block text-3xl font-black text-white bangers leading-none">{{ $user->friends()->count() }}</span>
                        <span class="text-xs font-black text-fuchsia-400 uppercase tracking-widest">Friends</span>
                    </div>
                    <div class="bg-white/10 px-6 py-3 jojo-border backdrop-blur-sm shadow-[4px_4px_0px_rgba(0,0,0,0.3)]">
                        <span class="block text-3xl font-black text-white bangers leading-none">{{ $user->watched_episodes_count }}</span>
                        <span class="text-xs font-black text-fuchsia-400 uppercase tracking-widest">Episodes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::id() === $user->id)
    <!-- Edit Profile Modal -->
    <div id="edit-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
        <div class="bg-white w-full max-w-2xl jojo-border jojo-shadow-lg overflow-hidden flex flex-col max-h-[90vh]">
            <div class="bg-purple-900 p-4 border-b-4 border-slate-900 flex justify-between items-center">
                <h3 class="text-3xl bangers text-yellow-400 uppercase tracking-widest">Edit Profile</h3>
                <button onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-white hover:text-yellow-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" class="p-8 space-y-8 overflow-y-auto">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-black text-fuchsia-600 uppercase tracking-widest mb-2">Username</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-slate-100 border-4 border-slate-900 p-3 bangers text-2xl text-purple-900 focus:outline-none focus:bg-yellow-50 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-fuchsia-600 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-slate-100 border-4 border-slate-900 p-3 bangers text-2xl text-purple-900 focus:outline-none focus:bg-yellow-50 transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-fuchsia-600 uppercase tracking-widest mb-4">Choose Your JoJo Identity</label>
                    <div class="grid grid-cols-4 gap-4">
                        @php
                            $avatars = [
                                'avatars/jonathan.png' => 'Jonathan',
                                'avatars/joseph.png' => 'Joseph',
                                'avatars/jotaro.png' => 'Jotaro',
                                'avatars/josuke4.png' => 'Josuke',
                                'avatars/giorno.png' => 'Giorno',
                                'avatars/jolyne.png' => 'Jolyne',
                                'avatars/johnny.png' => 'Johnny',
                                'avatars/gappy.png' => 'Gappy',
                            ];
                        @endphp
                        @foreach($avatars as $path => $name)
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="avatar_url" value="{{ $path }}" class="peer hidden" {{ $user->avatar_url === $path ? 'checked' : '' }}>
                                <div class="aspect-square bg-purple-100 border-4 border-slate-900 grayscale group-hover:grayscale-0 peer-checked:grayscale-0 peer-checked:border-fuchsia-600 peer-checked:bg-fuchsia-50 transition-all overflow-hidden relative">
                                    <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-fuchsia-600/20 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                <span class="block text-[10px] font-black text-center mt-1 uppercase text-slate-400 peer-checked:text-fuchsia-600 group-hover:text-slate-900">{{ $name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 text-white bangers text-3xl py-4 jojo-border jojo-shadow hover:bg-indigo-500 transform hover:-translate-y-1 transition-all uppercase tracking-widest">
                        Save Soul Data
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

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
                           class="bg-indigo-600 text-white font-black px-4 py-2 border-2 border-slate-900 text-xs uppercase tracking-widest hover:bg-indigo-500 shadow-[2px_2px_0px_#111] no-underline">
                            VIEW
                        </a>
                    </div>
                @empty
                    <div class="bg-purple-900/50 p-10 jojo-border text-center">
                        <p class="font-bold text-white uppercase tracking-widest">No favorites yet.</p>
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
                           class="bg-indigo-600 text-white font-black px-4 py-2 border-2 border-slate-900 text-xs uppercase tracking-widest hover:bg-indigo-500 shadow-[2px_2px_0px_#111] no-underline">
                            VIEW
                        </a>
                    </div>
                @empty
                    <div class="bg-purple-900/50 p-10 jojo-border text-center">
                        <p class="font-bold text-white uppercase tracking-widest">No watch history yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Friends List -->
    <div class="relative z-10 mb-20">
        <h3 class="text-4xl text-yellow-400 bangers transform -skew-x-6 mb-8 tracking-widest bg-indigo-600 inline-block px-6 py-2 jojo-border shadow-[4px_4px_0px_#111]">FRIENDS</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($user->friends() as $friend)
                <a href="{{ route('profile.show', $friend) }}" class="bg-white p-4 jojo-border jojo-shadow flex flex-col items-center group hover:bg-yellow-50 transition-all hover:-translate-y-1 no-underline">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center border-2 border-slate-900 overflow-hidden mb-3">
                        @if($friend->avatar_url)
                            <img src="{{ asset('storage/' . $friend->avatar_url) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl bangers text-purple-900">{{ substr($friend->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <span class="text-lg bangers text-purple-900 text-center truncate w-full uppercase tracking-widest">{{ $friend->name }}</span>
                </a>
            @empty
                <div class="col-span-full bg-purple-900/50 p-10 jojo-border text-center">
                    <p class="font-bold text-white uppercase tracking-widest">No friends yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
