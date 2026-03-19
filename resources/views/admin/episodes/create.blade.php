<x-layout>
    <x-slot:title>Create Episode</x-slot:title>

    <div class="mb-10 relative z-10">
        <a href="{{ route('admin.episodes.index') }}" class="inline-block bg-purple-900 text-yellow-400 font-black px-4 py-2 border-2 border-slate-900 mb-6 shadow-[2px_2px_0px_#111] hover:bg-purple-800 uppercase tracking-widest">
            ← BACK TO EPISODES
        </a>
        <h2 class="text-4xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-lg tracking-widest">Create Episode</h2>
    </div>

    <div class="bg-white p-8 jojo-border jojo-shadow relative z-10">
        <form action="{{ route('admin.episodes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-6">
                    <x-form.select name="part_id" label="Part" :options="$parts" display="title" />
                    <x-form.input name="title" label="Title" placeholder="Episode title..." />
                    <x-form.input name="episode_number" label="Episode Number" type="number" placeholder="1, 2, 3..." />
                    <x-form.input name="release_date" label="Air Date" type="date" />
                    <x-form.textarea name="summary" label="Summary" rows="4" placeholder="Episode description..." />
                </div>
                <div class="space-y-6">
                    <x-form.input name="imdb_score" label="IMDB Score" type="number" step="0.1" placeholder="8.5..." />
                    <x-form.input name="image" label="Episode Image" type="file" />
                </div>
            </div>

            <div class="flex justify-end pt-8 border-t-4 border-slate-900">
                <x-form.button color="jojo">SAVE EPISODE</x-form.button>
            </div>
        </form>
    </div>
</x-layout>
