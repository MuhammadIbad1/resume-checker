<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'ATS Pulse — AI Resume Auditor') }}</title>

    <!-- Dark & Light Mode Pre-load Script to avoid FOUC -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Google Fonts: Inter & Plus Jakarta Sans for AI aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        purple: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            950: '#2e1065',
                        },
                        violet: {
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                        }
                    }
                }
            }
        }
    </script>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col min-h-screen selection:bg-purple-500 selection:text-white transition-colors duration-200">
    
    <!-- Ambient Background Purple Glows -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-purple-200/40 dark:bg-purple-900/20 rounded-full blur-[140px]"></div>
        <div class="absolute top-1/3 -right-40 w-[500px] h-[500px] bg-violet-200/30 dark:bg-indigo-950/30 rounded-full blur-[140px]"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        @include('layouts.navigation')

        <!-- Flash Messages -->
        @if(session()->has('message'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
                <div class="p-4 rounded-2xl bg-purple-50 dark:bg-purple-950/50 border border-purple-200 dark:border-purple-800 text-purple-900 dark:text-purple-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-medium">{{ session('message') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <main class="flex-grow">
            {{ $slot }}
        </main>

        <footer class="border-t border-purple-100/80 dark:border-slate-800/80 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md py-8 mt-16 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-500 dark:text-slate-400">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600 inline-block shadow-sm shadow-purple-500/50"></span>
                    <span class="font-bold text-slate-900 dark:text-white">ATS Pulse AI</span>
                    <span>— Smart Resume Scoring & Job Match Platform</span>
                </div>
                <div>
                    &copy; {{ date('Y') }} TechDost SaaS Portfolio Engine. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    <!-- Theme Switcher JavaScript -->
    <script>
        function setupThemeToggle() {
            var toggleBtns = document.querySelectorAll('.theme-toggle-btn');
            
            toggleBtns.forEach(function(btn) {
                var darkIcon = btn.querySelector('.theme-toggle-dark-icon');
                var lightIcon = btn.querySelector('.theme-toggle-light-icon');

                function updateIcons() {
                    if (document.documentElement.classList.contains('dark')) {
                        lightIcon?.classList.remove('hidden');
                        darkIcon?.classList.add('hidden');
                    } else {
                        darkIcon?.classList.remove('hidden');
                        lightIcon?.classList.add('hidden');
                    }
                }

                updateIcons();

                btn.onclick = function() {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                    toggleBtns.forEach(function(b) {
                        var d = b.querySelector('.theme-toggle-dark-icon');
                        var l = b.querySelector('.theme-toggle-light-icon');
                        if (document.documentElement.classList.contains('dark')) {
                            l?.classList.remove('hidden');
                            d?.classList.add('hidden');
                        } else {
                            d?.classList.remove('hidden');
                            l?.classList.add('hidden');
                        }
                    });
                };
            });
        }

        document.addEventListener('DOMContentLoaded', setupThemeToggle);
        document.addEventListener('livewire:navigated', setupThemeToggle);
    </script>

    @livewireScripts
</body>
</html>
