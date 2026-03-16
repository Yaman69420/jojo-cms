<x-layout>
    <x-slot:title>Login</x-slot:title>

    <div class="max-w-2xl mx-auto py-20 relative z-10 text-center">
        <h2 class="text-6xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-xl tracking-widest">Login</h2>
        <p class="text-2xl font-bold text-fuchsia-300 mt-2 uppercase tracking-wider">Access your account to manage your favorites.</p>
    </div>

    <div class="max-w-md mx-auto bg-white p-8 jojo-border jojo-shadow mb-20 relative z-10">
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            
            <x-form.input name="email" label="Email Address" type="email" placeholder="ENTER YOUR EMAIL..." required />
            <x-form.input name="password" label="Password" type="password" placeholder="ENTER YOUR PASSWORD..." required />

            <div class="flex items-center justify-between">
                <label class="flex items-center font-bold text-purple-900 uppercase cursor-pointer">
                    <input type="checkbox" name="remember" class="w-6 h-6 border-4 border-slate-900 text-fuchsia-600 focus:ring-0 mr-3">
                    Remember Me
                </label>
            </div>

            <x-form.button color="jojo" class="w-full py-4 text-2xl tracking-widest">LOGIN</x-form.button>

            <div class="text-center pt-6 border-t-4 border-slate-900 mt-6">
                <p class="font-bold text-purple-900 uppercase">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-yellow-400 hover:text-yellow-300 underline font-black ml-2 text-lg">REGISTER HERE</a>
                </p>
            </div>
        </form>
    </div>
</x-layout>
