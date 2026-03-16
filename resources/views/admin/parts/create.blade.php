<x-layout>
    <x-slot:title>Create Part</x-slot:title>

    <div class="mb-10 relative z-10">
        <a href="{{ route('admin.parts.index') }}" class="inline-block bg-purple-900 text-yellow-400 font-black px-4 py-2 border-2 border-slate-900 mb-6 shadow-[2px_2px_0px_#111] hover:bg-purple-800 uppercase tracking-widest">
            ← BACK TO PARTS
        </a>
        <h2 class="text-4xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-lg tracking-widest">Create Part</h2>
    </div>

    <div class="bg-white p-8 jojo-border jojo-shadow relative z-10">
        <form action="{{ route('admin.parts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-6">
                    <x-form.input name="title" label="Title" placeholder="Part title..." />
                    <x-form.input name="number" label="Part Number" type="number" placeholder="1, 2, 3..." />
                    <x-form.input name="release_year" label="Release Year" type="number" placeholder="2024..." />
                    <x-form.textarea name="summary" label="Summary" rows="4" placeholder="Part description..." />
                </div>
                <div class="space-y-6">
                    <x-form.input name="trailer_url" label="Trailer URL (YouTube)" placeholder="https://youtube.com/..." />
                    <x-form.input name="image" label="Part Image" type="file" />
                </div>
            </div>

            <div class="flex justify-end pt-8 border-t-4 border-slate-900">
                <x-form.button color="jojo">CREATE PART</x-form.button>
            </div>
        </form>
    </div>
</x-layout>
