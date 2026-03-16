@props(['name', 'label', 'value' => ''])

<div class="mb-6 relative">
    <label for="{{ $name }}" class="block text-2xl font-bold bangers tracking-widest text-purple-900 mb-2 uppercase transform -skew-x-6 drop-shadow-sm">{{ $label }}</label>
    <div class="relative">
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            {{ $attributes->merge(['class' => 'block w-full bg-white text-purple-900 font-bold uppercase px-4 py-3 jojo-border shadow-[4px_4px_0px_rgba(107,33,168,1)] focus:ring-4 focus:ring-fuchsia-500 focus:outline-none placeholder-purple-300 transition-all']) }}
        >{{ old($name, $value) }}</textarea>
        <div class="absolute bottom-4 right-3 flex items-center pointer-events-none opacity-20">
            <span class="text-3xl font-black italic">ドド</span>
        </div>
    </div>
    @error($name)
        <p class="text-red-600 text-lg font-bold uppercase mt-2 drop-shadow">{{ $message }}</p>
    @enderror
</div>
