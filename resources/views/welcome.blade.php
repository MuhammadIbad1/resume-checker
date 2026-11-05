<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATS Pulse — AI Resume Auditor & Job Match Engine</title>

    <!-- Dark & Light Mode Pre-load Script to avoid FOUC -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col min-h-screen selection:bg-purple-600 selection:text-white transition-colors duration-200">

    <!-- Ambient Purple Soft Glows -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-purple-200/50 dark:bg-purple-900/20 rounded-full blur-[150px]"></div>
        <div class="absolute top-1/2 -right-40 w-[600px] h-[600px] bg-indigo-200/40 dark:bg-indigo-950/30 rounded-full blur-[160px]"></div>
    </div>

    <div class="relative z-10">
        <!-- Navbar -->
        <nav class="border-b border-purple-100/80 dark:border-slate-800/80 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl fixed top-0 w-full z-50 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-purple-500/25 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        ATS <span class="text-purple-600 dark:text-purple-400">Pulse</span>
                    </span>
                </a>

                <div class="flex items-center space-x-3 sm:space-x-4">
                    <!-- Theme Toggle Button -->
                    <button 
                        type="button" 
                        class="theme-toggle-btn p-2 rounded-xl text-slate-500 hover:text-purple-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-purple-400 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/60 focus:outline-none transition-colors" 
                        title="Toggle Dark and White mode"
                    >
                        <svg class="theme-toggle-light-icon hidden w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                        <svg class="theme-toggle-dark-icon hidden w-5 h-5 text-slate-600 dark:text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 transition shadow-lg shadow-purple-500/25">
                            Go to Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 px-3 py-2">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 transition shadow-lg shadow-purple-500/25">
                            Try 3 Free Scans
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-36 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
            
            <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full text-xs font-bold bg-purple-100/80 dark:bg-purple-950/80 text-purple-800 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800 shadow-sm mb-8">
                <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                <span>AI-Powered ATS Resume Auditor & Security Checker</span>
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-none max-w-5xl mx-auto">
                Bypass Automated Filters with <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-600 dark:from-purple-400 dark:to-indigo-400">AI Resume Scoring</span>
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Upload your resume PDF to test against 6 core ATS scoring & security categories, action verb density, measurable metrics, PII protection, and target job description keywords.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl font-extrabold text-base bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 transition shadow-xl shadow-purple-500/25">
                    Scan Your Resume Free →
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-2xl font-bold text-base bg-white dark:bg-slate-900 border border-purple-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-200 hover:text-purple-700 dark:hover:text-purple-400 hover:bg-purple-50/50 dark:hover:bg-slate-800 transition shadow-sm">
                    Explore 6 Scoring Rules
                </a>
            </div>

            <!-- Demo Score Gauge Preview Card -->
            <div class="mt-16 max-w-4xl mx-auto bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-8 shadow-2xl shadow-purple-500/5 text-left grid grid-cols-1 md:grid-cols-12 gap-8 items-center transition-colors">
                <div class="md:col-span-4 flex flex-col items-center justify-center p-6 rounded-2xl bg-purple-50/60 dark:bg-slate-800/80 border border-purple-100 dark:border-slate-700">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" stroke="#ede9fe" class="dark:stroke-slate-700" stroke-width="8" fill="transparent"/>
                            <circle cx="50" cy="50" r="40" stroke="#7c3aed" stroke-width="8" stroke-linecap="round" stroke-dasharray="251" stroke-dashoffset="30" fill="transparent"/>
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-4xl font-black text-slate-900 dark:text-white">88</span>
                            <span class="text-[10px] font-bold text-purple-700 dark:text-purple-400 uppercase">out of 100</span>
                        </div>
                    </div>
                    <span class="mt-3 px-3 py-1 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800">High ATS Match</span>
                </div>

                <div class="md:col-span-8 space-y-4">
                    <div class="flex items-center space-x-2 text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider">
                        <span>✦ Real-Time AI Rule & Security Analysis</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Senior Software Engineer CV Audit</h3>
                    <div class="space-y-3 text-xs text-slate-600 dark:text-slate-300">
                        <div>
                            <div class="flex justify-between font-semibold mb-1">
                                <span>Structure & Heading Parsing</span>
                                <span class="text-purple-700 dark:text-purple-400 font-bold">95%</span>
                            </div>
                            <div class="w-full h-2 bg-purple-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-600 rounded-full" style="width: 95%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between font-semibold mb-1">
                                <span>Security, PII & Integrity Check</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">100% Passed</span>
                            </div>
                            <div class="w-full h-2 bg-purple-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between font-semibold mb-1">
                                <span>Target Job Keyword Match</span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-bold">82%</span>
                            </div>
                            <div class="w-full h-2 bg-purple-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-600 rounded-full" style="width: 82%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- 6 Core ATS Categories Grid -->
        <section id="features" class="py-20 bg-white dark:bg-slate-900 border-y border-purple-100/80 dark:border-slate-800/80 transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
                <div class="text-center space-y-3">
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">6 Core ATS Scoring & Security Categories</h2>
                    <p class="text-slate-600 dark:text-slate-400 text-sm max-w-xl mx-auto">
                        Configurable real-time scoring rules tuned directly via the Filament Admin Console.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-purple-100 dark:border-slate-700/60 rounded-2xl p-6 space-y-3 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xl font-bold">📄</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">1. Structure & Format</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">Standard headings, readable PDF format, word count limits, and section detection.</p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-purple-100 dark:border-slate-700/60 rounded-2xl p-6 space-y-3 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xl font-bold">📇</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">2. Contact Info</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">Email, phone, customized LinkedIn profile link, and geographical location verification.</p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-purple-100 dark:border-slate-700/60 rounded-2xl p-6 space-y-3 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xl font-bold">⚡</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">3. Action Verbs</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">Detects high-impact action verbs (Architected, Spearheaded, Optimized, Scaled).</p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-purple-100 dark:border-slate-700/60 rounded-2xl p-6 space-y-3 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xl font-bold">📊</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">4. Quantifiable Metrics</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">Identifies measurable business results: percentages, revenue numbers, and scale metrics.</p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-purple-100 dark:border-slate-700/60 rounded-2xl p-6 space-y-3 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xl font-bold">🎯</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">5. Job Keyword Match</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">Compares skills against target JD to show keyword match density and missing core terms.</p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-purple-100 dark:border-slate-700/60 rounded-2xl p-6 space-y-3 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xl font-bold">🛡️</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">6. Security & Privacy</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">Checks for PII exposure (SSN/CNIC/DOB), prompt injection defense, credential leaks, and safe links.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-8 text-center text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-950 transition-colors">
            <p>&copy; {{ date('Y') }} TechDost SaaS Portfolio Engine — Laravel 12 + Livewire 3 + Filament v3</p>
        </footer>
    </div>

    <!-- Theme Switcher JavaScript -->
    <script>
        function setupLandingThemeToggle() {
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

        document.addEventListener('DOMContentLoaded', setupLandingThemeToggle);
    </script>
</body>
</html>
