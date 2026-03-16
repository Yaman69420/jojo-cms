@if (session()->has('success'))
    <div class="bg-yellow-400 border-4 border-slate-900 text-purple-900 px-6 py-4 relative mb-6 shadow-[6px_6px_0px_#111] transform -rotate-1" role="alert">
        <strong class="font-bold bangers text-3xl tracking-widest uppercase block drop-shadow-sm mb-1">Success!</strong>
        <span class="block sm:inline font-bold text-lg uppercase">{{ session('success') }}</span>
    </div>
@endif

@if (session()->has('error'))
    <div class="bg-red-600 border-4 border-slate-900 text-white px-6 py-4 relative mb-6 shadow-[6px_6px_0px_#111] transform rotate-1" role="alert">
        <strong class="font-bold bangers text-3xl tracking-widest uppercase block drop-shadow-sm mb-1">Error!</strong>
        <span class="block sm:inline font-bold text-lg uppercase">{{ session('error') }}</span>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="bg-red-600 border-4 border-slate-900 text-white px-6 py-4 relative mb-6 shadow-[6px_6px_0px_#111] transform rotate-1" role="alert">
        <strong class="font-bold bangers text-3xl tracking-widest uppercase block drop-shadow-sm mb-2">Validation Error!</strong>
        <ul class="list-none space-y-1">
            @foreach ($errors->all() as $error)
                <li class="font-bold text-lg uppercase flex items-center">
                    <span class="text-yellow-400 mr-2 text-xl">★</span> {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
