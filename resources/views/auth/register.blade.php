<x-layout>
    <x-slot:title>Register</x-slot:title>

    <div class="max-w-2xl mx-auto py-20 relative z-10 text-center">
        <h2 class="text-6xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-xl tracking-widest">Register</h2>
        <p class="text-2xl font-bold text-fuchsia-300 mt-2 uppercase tracking-wider">Join us to track your watched episodes.</p>
    </div>

    <div class="max-w-md mx-auto bg-white p-8 jojo-border jojo-shadow mb-20 relative z-10">
        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf
            
            <x-form.input name="name" label="Display Name" placeholder="CHOOSE A NAME..." required />
            <x-form.input name="email" label="Email Address" type="email" placeholder="YOUR EMAIL..." required />
            <x-form.input name="password" label="Password" type="password" placeholder="MIN 8 CHARACTERS..." required />
            <x-form.input name="password_confirmation" label="Confirm Password" type="password" placeholder="REPEAT PASSWORD..." required />

            <x-form.button color="jojo" class="w-full py-4 text-2xl tracking-widest">REGISTER</x-form.button>

            <div class="text-center pt-6 border-t-4 border-slate-900 mt-6">
                <p class="font-bold text-purple-900 uppercase">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300 underline font-black ml-2 text-lg">LOGIN HERE</a>
                </p>
            </div>
        </form>
    </div>
</x-layout>
