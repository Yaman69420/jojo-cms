<x-layout>
    <x-slot:title>Edit Episode</x-slot:title>

    <div class="mb-10 relative z-10">
        <a href="{{ route('admin.episodes.index') }}" class="inline-block bg-purple-900 text-yellow-400 font-black px-4 py-2 border-2 border-slate-900 mb-6 shadow-[2px_2px_0px_#111] hover:bg-purple-800 uppercase tracking-widest">
            ← BACK TO EPISODES
        </a>
        <h2 class="text-4xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-lg tracking-widest">Edit Episode</h2>
    </div>

    <div class="bg-white p-8 jojo-border jojo-shadow relative z-10">
        <form action="{{ route('admin.episodes.update', $episode) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-6">
                    <x-form.select name="part_id" label="Part" :options="$parts" :value="$episode->part_id" display="title" />
                    <x-form.input name="title" label="Title" :value="$episode->title" />
                    <x-form.input name="episode_number" label="Episode Number" type="number" :value="$episode->episode_number" />
                    <x-form.input name="release_date" label="Air Date" type="date" :value="$episode->release_date" />
                    <x-form.textarea name="summary" label="Summary" rows="4" :value="$episode->summary" />
                </div>
                <div class="space-y-6">
                    <x-form.input name="imdb_score" label="IMDB Score" type="number" step="0.1" :value="$episode->imdb_score" />
                    <div class="flex flex-col gap-4">
                        <x-form.input name="image" label="Episode Image" type="file" />
                        @if($episode->media->first())
                            <div class="w-48">
                                <span class="block text-xs font-black text-slate-500 uppercase mb-2">Current Image:</span>
                                <img src="{{ asset('storage/' . $episode->media->first()->path) }}" class="w-full jojo-border">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-8 border-t-4 border-slate-900">
                <x-form.button color="jojo">OVERWRITE CHANGES</x-form.button>
            </div>
        </form>
    </div>
</x-layout>
