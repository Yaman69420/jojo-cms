@props(['name', 'label', 'options', 'value' => '', 'key' => 'id', 'display' => 'name'])

<div class="mb-6 relative">
    <label for="{{ $name }}" class="block text-2xl font-bold bangers tracking-widest text-purple-900 mb-2 uppercase transform -skew-x-6 drop-shadow-sm">{{ $label }}</label>
    <div class="relative">
        <select 
            name="{{ $name }}" 
            id="{{ $name }}" 
            {{ $attributes->merge(['class' => 'block w-full bg-white text-purple-900 font-bold uppercase px-4 py-3 jojo-border shadow-[4px_4px_0px_rgba(107,33,168,1)] focus:ring-4 focus:ring-fuchsia-500 focus:outline-none transition-all appearance-none cursor-pointer']) }}
        >
            <option value="">SELECT {{ strtoupper($label) }}</option>
            @foreach($options as $option)
                <option value="{{ $option->$key }}" {{ old($name, $value) == $option->$key ? 'selected' : '' }} class="font-bold text-lg">
                    {{ strtoupper($option->$display) }}
                </option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none bg-yellow-400 jojo-border border-y-0 border-r-0 text-purple-900 font-bold">
            ▼
        </div>
    </div>
    @error($name)
        <p class="text-red-600 text-lg font-bold uppercase mt-2 drop-shadow">{{ $message }}</p>
    @enderror
</div>
