<x-layout>
    <x-slot:title>Friend Requests</x-slot:title>

    <div class="mb-12 relative z-10">
        <div class="bg-purple-900 p-8 jojo-border jojo-shadow relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 text-white opacity-5 select-none pointer-events-none text-9xl bangers rotate-12">REQUESTS</div>
            <h2 class="text-5xl text-yellow-400 bangers transform -skew-x-6 tracking-widest drop-shadow-lg mb-2">PENDING REQUESTS</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative z-10 mb-20">
        @forelse($requests as $user)
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
                        <span class="text-xs font-black text-fuchsia-400 uppercase tracking-widest">{{ $user->email }}</span>
                    </div>
                </div>
                
                <div class="p-6 flex-1 flex flex-col justify-between gap-6">
                    <p class="text-slate-500 font-bold italic">Sent {{ $user->pivot->created_at->diffForHumans() }}</p>
                    
                    <div class="flex gap-4">
                        <form action="{{ route('friendships.accept', $user) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white bangers text-xl py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-green-500 transition-colors uppercase tracking-widest">
                                Accept
                            </button>
                        </form>
                        <form action="{{ route('friendships.reject', $user) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white bangers text-xl py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-red-500 transition-colors uppercase tracking-widest">
                                Reject
                            </button>
                        </form>
                    </div>
                    <a href="{{ route('profile.show', $user) }}" class="text-center text-indigo-600 font-black uppercase tracking-widest text-xs hover:text-indigo-500">View Profile</a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-purple-900/50 p-20 jojo-border text-center">
                <p class="text-3xl text-white bangers uppercase tracking-widest mb-4">No pending friend requests.</p>
                <a href="{{ route('profile.index') }}" class="text-yellow-400 underline bangers text-xl uppercase tracking-widest hover:text-yellow-300">Find Friends</a>
            </div>
        @endforelse
    </div>
</x-layout>
