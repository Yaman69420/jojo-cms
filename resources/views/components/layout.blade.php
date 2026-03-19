<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'JoJo CMS' }}</title>

    <!-- Fonts: Preconnect to speed up discovery -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=oswald:400,500,700|bangers:400" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { 
            font-family: 'Oswald', sans-serif; 
            background-color: #2b0a3d;
            background-image: radial-gradient(#ff00a0 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .bangers { font-family: 'Bangers', cursive; letter-spacing: 2px; }
        .jojo-shadow {
            box-shadow: 6px 6px 0px #ffd700;
        }
        .jojo-border {
            border: 3px solid #111;
        }
        /* Hide scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-screen text-slate-100 selection:bg-fuchsia-500 selection:text-white overflow-hidden">
    <div class="flex h-screen overflow-hidden relative">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-purple-950/80 backdrop-blur-sm z-30 lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:static inset-y-0 left-0 w-64 bg-fuchsia-900 text-white flex flex-col jojo-border border-l-0 border-t-0 border-b-0 border-r-4 border-slate-900 z-40 transition-transform duration-300 ease-in-out overflow-hidden">
            
            <div class="absolute top-0 right-0 p-2 opacity-20 text-4xl font-bold">★</div>
            
            <a href="{{ route('home') }}" class="h-20 flex items-center px-6 border-b-4 border-slate-900 bg-purple-950 hover:bg-purple-900 transition-colors shrink-0">
                <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center mr-3 font-bold text-2xl text-purple-900 jojo-border shadow-[2px_2px_0px_#111]">★</div>
                <span class="text-3xl text-yellow-400 bangers transform -skew-x-6 drop-shadow-md">JOJO CMS</span>
            </a>

            <nav class="flex-1 px-4 py-6 space-y-4 overflow-y-auto no-scrollbar relative z-10">
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" @click="sidebarOpen = false">
                    <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-xl uppercase tracking-wider">Home</span>
                </x-nav-link>

                @if(request()->is('admin*'))
                    <div class="px-2 text-xs font-black text-yellow-400 uppercase tracking-widest mb-2 opacity-70">ADMIN VIEWS</div>
                    <x-nav-link href="{{ route('admin.parts.index') }}" :active="request()->routeIs('admin.parts.*')" @click="sidebarOpen = false">
                        <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20"></path></svg>
                        <span class="text-xl uppercase tracking-wider">Parts</span>
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.episodes.index') }}" :active="request()->routeIs('admin.episodes.*')" @click="sidebarOpen = false">
                        <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="text-xl uppercase tracking-wider">Episodes</span>
                    </x-nav-link>
                    <div class="mt-8 pt-4 border-t-2 border-fuchsia-800">
                        <x-nav-link href="{{ route('parts.index') }}" :active="false" @click="sidebarOpen = false">
                            <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span class="text-xl uppercase tracking-wider">Public Site</span>
                        </x-nav-link>
                    </div>
                @else
                    <div class="px-2 text-xs font-black text-yellow-400 uppercase tracking-widest mb-2 opacity-70">PUBLIC VIEWS</div>
                    <x-nav-link href="{{ route('parts.index') }}" :active="request()->routeIs('parts.*')" @click="sidebarOpen = false">
                        <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20"></path></svg>
                        <span class="text-xl uppercase tracking-wider">Parts</span>
                    </x-nav-link>
                    <x-nav-link href="{{ route('episodes.index') }}" :active="request()->routeIs('episodes.*')" @click="sidebarOpen = false">
                        <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="text-xl uppercase tracking-wider">Episodes</span>
                    </x-nav-link>

                    <x-nav-link href="{{ route('profile.index') }}" :active="request()->routeIs('profile.index')" @click="sidebarOpen = false">
                        <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-xl uppercase tracking-wider">Community</span>
                    </x-nav-link>

                    @auth
                        @php
                            $pendingCount = Auth::user()->friendshipsReceived()->wherePivot('status', 'pending')->count();
                        @endphp
                        <x-nav-link href="{{ route('friend-requests.index') }}" :active="request()->routeIs('friend-requests.*')" @click="sidebarOpen = false">
                            <div class="relative flex items-center">
                                <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                <span class="text-xl uppercase tracking-wider">Requests</span>
                                @if($pendingCount > 0)
                                    <span class="ml-2 bg-yellow-400 text-purple-900 text-[10px] font-black px-1.5 py-0.5 rounded-full jojo-border shadow-[1px_1px_0px_#111] animate-pulse">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </div>
                        </x-nav-link>

                        <x-nav-link href="{{ route('chat') }}" :active="request()->routeIs('chat')" @click="sidebarOpen = false">
                            <div class="relative flex items-center">
                                <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                <span class="text-xl uppercase tracking-wider">Messaging</span>
                                <livewire:chat-badge />
                            </div>
                        </x-nav-link>
                    @endauth

                    <div class="px-4 py-2">
                        <form action="{{ route('profile.index') }}" method="GET" class="relative group">
                            <input type="text" name="search" placeholder="Search users..." 
                                   class="w-full bg-purple-950/50 border-2 border-slate-900 px-3 py-1.5 text-sm bangers tracking-widest text-yellow-400 placeholder-purple-400/50 focus:outline-none focus:border-yellow-400 transition-colors">
                            <button type="submit" class="absolute right-2 top-1.5 text-purple-400 group-hover:text-yellow-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </form>
                    </div>
                    
                    @auth
                        @can('admin')
                        <div class="mt-8 pt-4 border-t-2 border-fuchsia-800">
                            <x-nav-link href="{{ route('admin.parts.index') }}" :active="false" @click="sidebarOpen = false">
                                <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-xl uppercase tracking-wider">Admin Panel</span>
                            </x-nav-link>
                        </div>
                        @endcan
                    @endauth
                @endif
                
                <div class="mt-8 pt-4 border-t-2 border-fuchsia-800">
                    <div class="px-2 text-xs font-black text-yellow-400 uppercase tracking-widest mb-2 opacity-70">ACCOUNT</div>
                    @guest
                        <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')" @click="sidebarOpen = false">
                            <span class="text-xl uppercase tracking-wider font-bold">Login</span>
                        </x-nav-link>
                        <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')" @click="sidebarOpen = false">
                            <span class="text-xl uppercase tracking-wider font-bold">Register</span>
                        </x-nav-link>
                    @endguest

                    @auth
                        <div class="mb-4">
                            <x-nav-link href="{{ route('profile.show', auth()->user()) }}" :active="request()->routeIs('profile.show')" @click="sidebarOpen = false">
                                <svg class="w-6 h-6 mr-3 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="text-xl font-black uppercase tracking-widest">Profile</span>
                            </x-nav-link>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 flex items-center text-slate-300 hover:bg-yellow-400 hover:text-purple-900 transition-colors uppercase font-black tracking-widest text-xl group">
                                <span class="group-hover:transform group-hover:translate-x-2 transition-transform">Logout</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <!-- Top Header -->
            <header class="h-20 bg-yellow-400 jojo-border border-t-0 border-l-0 border-r-0 border-b-4 flex items-center justify-between px-4 lg:px-8 z-30 relative shrink-0">
                <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHRleHQgeT0iMjAiIGZvbnQtc2l6ZT0iMjAiIGZpbGw9IiMwMDAiPuOCtDwvdGV4dD48L3N2Zz4=')]"></div>
                
                <div class="flex items-center gap-4 relative z-10">
                    <!-- Hamburger Menu -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 bg-purple-900 text-yellow-400 jojo-border shadow-[2px_2px_0px_#111] hover:bg-purple-800 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <h1 class="text-xl lg:text-3xl text-purple-900 bangers uppercase transform -skew-x-6 drop-shadow relative z-10 truncate max-w-[150px] sm:max-w-none">
                        {{ $title ?? 'Dashboard' }}
                    </h1>
                </div>

                <div class="flex items-center space-x-4 relative z-10">
                    <div class="flex items-center space-x-2 lg:space-x-3 bg-purple-900 jojo-border px-3 lg:px-4 py-1.5 lg:py-2 text-yellow-400 jojo-shadow">
                        @auth
                            <div class="w-6 h-6 lg:w-8 lg:h-8 bg-yellow-400 rounded-full flex items-center justify-center text-purple-900 font-bold text-sm lg:text-xl bangers uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm lg:text-lg font-bold uppercase tracking-widest truncate max-w-[80px] lg:max-w-none">{{ auth()->user()->name }}</span>
                        @else
                            <div class="w-6 h-6 lg:w-8 lg:h-8 bg-yellow-400 rounded-full flex items-center justify-center text-purple-900 font-bold text-sm lg:text-xl bangers uppercase">
                                ?
                            </div>
                            <span class="text-sm lg:text-lg font-bold uppercase tracking-widest">GUEST</span>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8 relative no-scrollbar">
                <div class="absolute top-10 right-10 text-fuchsia-600/20 text-6xl lg:text-8xl font-black transform rotate-12 pointer-events-none select-none z-0">ゴゴゴゴ</div>
                <div class="absolute bottom-20 left-10 text-yellow-500/10 text-7xl lg:text-9xl font-black transform -rotate-12 pointer-events-none select-none z-0">ドドド</div>
                <div class="max-w-6xl mx-auto relative z-10">
                    <x-flash />
                    {{ $slot }}

                    <!-- Footer -->
                    <footer class="mt-20 pt-16 pb-8 border-t-4 border-slate-900/50">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-12">
                            <div>
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center mr-2 font-bold text-lg text-purple-900 jojo-border shadow-[2px_2px_0px_#111]">★</div>
                                    <span class="text-3xl text-yellow-400 bangers transform -skew-x-6">JOJO CMS</span>
                                </div>
                                <p class="text-slate-400 max-w-sm font-bold uppercase tracking-widest text-sm leading-relaxed">
                                    The ultimate Community for JoJo fans. Explore the history of the Joestars.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-black uppercase tracking-[0.2em]">
                            <p class="text-slate-500">
                                &copy; {{ date('Y') }} <span class="text-yellow-400/50">JOJO CMS</span>. ALL RIGHTS RESERVED.
                            </p>
                            <p class="text-fuchsia-600/40">
                                INSPIRED BY THE LEGENDARY <a href="https://www.google.com/search?q=Hirohiko+Araki" target="_blank" class="text-fuchsia-400/60 hover:text-fuchsia-400 transition-colors cursor-help decoration-fuchsia-500/30 underline-offset-4 hover:underline" title="The Creator of JoJo">HIROHIKO ARAKI</a>
                            </p>
                            <p class="text-slate-500">
                                CREATED BY <span class="text-white">YAMAN</span>
                            </p>
                        </div>
                    </footer>
            </main>
        </div>
    </div>
    @livewireScripts
@auth
    <livewire:chat-floating />
@endauth
</body>
</html>
