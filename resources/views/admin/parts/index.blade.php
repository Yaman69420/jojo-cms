<x-layout>
    <x-slot:title>Manage Parts</x-slot:title>

    <div class="mb-10 flex flex-col sm:flex-row justify-between items-center gap-4 relative z-10">
        <div>
            <h2 class="text-4xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-lg tracking-widest text-shadow-lg">Manage Parts</h2>
            <p class="text-lg font-bold text-fuchsia-300 mt-1 uppercase tracking-wider">Administrative control over the series parts.</p>
        </div>
        @can('admin')
        <div class="flex gap-4">
            <a href="{{ route('admin.parts.create') }}" class="bg-yellow-400 text-purple-900 bangers text-2xl px-6 py-2 jojo-border jojo-shadow hover:bg-yellow-300 transform hover:-translate-y-1 transition-transform uppercase tracking-widest">Create Part</a>
        </div>
        @endcan
    </div>

    <!-- Parts Grid (Matching Public Style) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative z-10">
        @forelse($parts as $part)
            <div class="bg-white text-purple-900 jojo-border jojo-shadow overflow-hidden group hover:scale-[1.02] transition-transform flex flex-col">
                <div class="h-48 bg-purple-900 relative overflow-hidden flex items-center justify-center border-b-4 border-slate-900">
                    @if($part->media->first())
                        <img src="{{ asset('storage/' . $part->media->first()->path) }}" class="object-cover w-full h-full opacity-80 group-hover:opacity-100 transition-opacity">
                    @else
                        <div class="text-6xl font-black text-yellow-400 opacity-20">PART {{ $part->number }}</div>
                    @endif
                    <div class="absolute top-4 left-4 bg-yellow-400 text-purple-900 bangers text-2xl px-3 py-1 jojo-border">
                        PART {{ $part->number }}
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-3xl bangers uppercase tracking-widest mb-2 leading-tight">{{ $part->title }}</h3>
                    <div class="text-sm font-black text-fuchsia-600 uppercase mb-4">{{ $part->release_year }}</div>
                    <p class="text-slate-600 font-bold text-sm mb-6 flex-1">{{ Str::limit($part->summary, 120) }}</p>
                    
                    <div class="flex items-center justify-between pt-4 border-t-2 border-slate-100 gap-2">
                        <a href="{{ route('admin.parts.show', $part) }}" class="text-indigo-600 font-black uppercase tracking-widest hover:underline text-sm">VIEW</a>
                        @can('admin')
                        <a href="{{ route('admin.parts.edit', $part) }}" class="text-yellow-600 font-black uppercase tracking-widest hover:underline text-sm">EDIT</a>
                        <form action="{{ route('admin.parts.destroy', $part) }}" method="POST" onsubmit="return confirm('Delete this part?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 font-black uppercase tracking-widest hover:underline text-sm">DELETE</button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-purple-900 text-yellow-400 text-center jojo-border jojo-shadow">
                <div class="text-6xl bangers mb-4 transform -skew-x-6">NO RESULTS</div>
                <p class="text-2xl font-bold uppercase tracking-widest">No parts found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $parts->links() }}
    </div>
</x-layout>
