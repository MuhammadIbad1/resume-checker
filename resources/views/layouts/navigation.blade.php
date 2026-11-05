<nav class="border-b border-purple-100/80 dark:border-slate-800/80 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl sticky top-0 z-50 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo & Navigation Links -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-600 via-purple-700 to-indigo-600 flex items-center justify-center shadow-lg shadow-purple-500/25 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        ATS <span class="text-purple-600 dark:text-purple-400">Pulse</span>
                    </span>
                </a>

                @auth
                    <div class="hidden md:flex items-center space-x-2">
                        <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-purple-50 dark:bg-purple-950/70 text-purple-700 dark:text-purple-300 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/60 dark:hover:bg-slate-800/60' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('scanner') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('scanner') ? 'bg-purple-50 dark:bg-purple-950/70 text-purple-700 dark:text-purple-300 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/60 dark:hover:bg-slate-800/60' }}">
                            AI Resume Scanner
                        </a>
                        <a href="{{ route('resumes.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('resumes.*') || request()->routeIs('builder.*') ? 'bg-purple-50 dark:bg-purple-950/70 text-purple-700 dark:text-purple-300 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/60 dark:hover:bg-slate-800/60' }}">
                            Build ATS Resume
                        </a>
                        <a href="{{ route('billing') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('billing') ? 'bg-purple-50 dark:bg-purple-950/70 text-purple-700 dark:text-purple-300 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/60 dark:hover:bg-slate-800/60' }}">
                            Plans & Pricing
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Right Controls: Theme Toggle + User / Auth -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <!-- Theme Toggle Button (Dark & White Mode) -->
                <button 
                    type="button" 
                    class="theme-toggle-btn p-2 rounded-xl text-slate-500 hover:text-purple-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-purple-400 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/60 focus:outline-none transition-colors" 
                    title="Toggle Dark and White mode"
                >
                    <!-- Sun Icon (Shows in Dark Mode) -->
                    <svg class="theme-toggle-light-icon hidden w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                    <!-- Moon Icon (Shows in Light / White Mode) -->
                    <svg class="theme-toggle-dark-icon hidden w-5 h-5 text-slate-600 dark:text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="/admin" target="_blank" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-100/80 dark:bg-purple-950/80 border border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-300 hover:bg-purple-200/60 transition flex items-center space-x-1.5 shadow-sm">
                            <span>⚡ Admin</span>
                        </a>
                    @endif

                    <div class="flex items-center space-x-3 pl-3 border-l border-slate-200 dark:border-slate-800">
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 px-3 py-2">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 shadow-lg shadow-purple-500/25 transition">
                        Start Free Audit
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
