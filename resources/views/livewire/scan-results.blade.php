<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-purple-100 dark:border-slate-800 pb-6 transition-colors">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 flex items-center space-x-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span>Back to Dashboard</span>
                </a>
                <span class="text-slate-300 dark:text-slate-700">•</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Scanned {{ $scan->created_at->diffForHumans() }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                ATS Audit Report: <span class="text-purple-700 dark:text-purple-400">{{ $scan->file_name }}</span>
            </h1>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('scanner') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-white dark:bg-slate-900 border border-purple-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-slate-800 transition shadow-sm">
                + Scan Another CV
            </a>
            <button onclick="window.print()" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-purple-100/80 dark:bg-purple-950/80 border border-purple-200 dark:border-purple-800 text-purple-900 dark:text-purple-200 hover:bg-purple-200/60 dark:hover:bg-purple-900/60 transition flex items-center space-x-2 shadow-sm">
                <svg class="w-4 h-4 text-purple-700 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print PDF Report</span>
            </button>
        </div>
    </div>

    <!-- Security Risks Banner Alert (if any security checker flagged an issue) -->
    @php
        $securityResults = $scan->results->where('category', 'security');
        $failedSecurity = $securityResults->where('passed', false);
    @endphp

    @if($failedSecurity->count() > 0)
        <div class="p-5 rounded-3xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-900 dark:text-rose-200 shadow-lg shadow-rose-500/5">
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 rounded-2xl bg-rose-100 dark:bg-rose-900/60 text-rose-600 dark:text-rose-300 flex items-center justify-center shrink-0 font-bold text-xl">
                    🛡️
                </div>
                <div class="space-y-1.5 flex-grow">
                    <h3 class="text-base font-extrabold text-rose-950 dark:text-rose-100">
                        Critical ATS Security & Privacy Alert ({{ $failedSecurity->count() }} Red Flags Detected)
                    </h3>
                    <p class="text-xs text-rose-800 dark:text-rose-300 leading-relaxed">
                        Enterprise ATS scanners (Workday, Greenhouse, Taleo) automatically flag or discard resumes with prompt injections, exposed personal credentials, or sensitive PII. Please review the Security Checker tab below before submitting.
                    </p>
                    <div class="pt-2 flex flex-wrap gap-2">
                        @foreach($failedSecurity as $secIssue)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-200/80 dark:bg-rose-900/80 text-rose-950 dark:text-rose-100">
                                ⚠ {{ $secIssue->rule_name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Banner Grid: Score Ring + Category Scores -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Score Gauge Card (4 columns) -->
        <div class="lg:col-span-4 bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-8 flex flex-col items-center justify-center text-center shadow-xl shadow-purple-500/5 relative overflow-hidden transition-colors">
            @php
                $score = $scan->overall_score;
                $strokeColor = $score >= 80 ? '#7c3aed' : ($score >= 60 ? '#f59e0b' : '#f43f5e');
                $badgeBg = $score >= 80 
                    ? 'bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border-purple-200 dark:border-purple-800' 
                    : ($score >= 60 
                        ? 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800' 
                        : 'bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-800');
            @endphp

            <div class="absolute -right-12 -top-12 w-40 h-40 rounded-full bg-purple-200/40 dark:bg-purple-900/20 blur-3xl pointer-events-none"></div>

            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-6">Overall ATS Compatibility Score</h3>

            <!-- SVG Gauge Ring -->
            <div class="relative w-44 h-44 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="42" stroke="#ede9fe" class="dark:stroke-slate-800" stroke-width="8" fill="transparent"/>
                    <circle 
                        cx="50" cy="50" r="42" 
                        stroke="{{ $strokeColor }}" 
                        stroke-width="8" 
                        stroke-linecap="round"
                        stroke-dasharray="264"
                        stroke-dashoffset="{{ 264 - (264 * $score) / 100 }}"
                        fill="transparent"
                        class="transition-all duration-1000 ease-out"
                    />
                </svg>
                <div class="absolute flex flex-col items-center">
                    <span class="text-5xl font-black text-slate-900 dark:text-white tracking-tight">{{ $score }}</span>
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">out of 100</span>
                </div>
            </div>

            <div class="mt-6">
                @if($score >= 80)
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold border {{ $badgeBg }}">
                        🚀 High Interview Likelihood
                    </span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Resume is well formatted and passes top ATS recruiter filters.</p>
                @elseif($score >= 60)
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold border {{ $badgeBg }}">
                        ⚠️ Moderate ATS Compatibility
                    </span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Passes basic checks, but needs action verbs & keyword optimization.</p>
                @else
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold border {{ $badgeBg }}">
                        ❌ High Risk of ATS Auto-Rejection
                    </span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Fix red-flag formatting, security alerts, and missing sections below.</p>
                @endif
            </div>
        </div>

        <!-- 6 Category Progress Bars (8 columns) -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-8 shadow-xl shadow-purple-500/5 flex flex-col justify-between transition-colors">
            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-200 mb-6">Category Score Breakdown</h3>

                <div class="space-y-4">
                    @php
                        $catLabels = [
                            'structure' => ['name' => 'Structure & Formatting', 'icon' => '📄'],
                            'contact' => ['name' => 'Contact & Info Completeness', 'icon' => '📇'],
                            'experience' => ['name' => 'Experience & Action Verbs', 'icon' => '⚡'],
                            'metrics' => ['name' => 'Quantifiable Metrics & Data', 'icon' => '📊'],
                            'keywords' => ['name' => 'Job Keyword Match Density', 'icon' => '🎯'],
                            'security' => ['name' => 'Security & Privacy Defense', 'icon' => '🛡️'],
                        ];
                    @endphp

                    @foreach(($scan->category_scores ?? []) as $catKey => $catPct)
                        @php
                            $catInfo = $catLabels[$catKey] ?? ['name' => ucfirst($catKey), 'icon' => '📌'];
                            $barColor = $catPct >= 80 ? 'bg-purple-600' : ($catPct >= 60 ? 'bg-amber-500' : 'bg-rose-500');
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-sm font-bold mb-1.5">
                                <span class="text-slate-700 dark:text-slate-300 flex items-center space-x-2">
                                    <span>{{ $catInfo['icon'] }}</span>
                                    <span>{{ $catInfo['name'] }}</span>
                                </span>
                                <span class="text-slate-900 dark:text-white font-mono font-extrabold">{{ $catPct }}%</span>
                            </div>
                            <div class="w-full h-2.5 bg-purple-50 dark:bg-slate-800 rounded-full overflow-hidden border border-purple-100 dark:border-slate-700/60 p-0.5">
                                <div class="h-full {{ $barColor }} rounded-full transition-all duration-700" style="width: {{ $catPct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-purple-100 dark:border-slate-800 flex items-center space-x-2 sm:space-x-4 overflow-x-auto transition-colors">
        <button 
            wire:click="setTab('breakdown')" 
            class="py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold border-b-2 transition flex items-center space-x-2 whitespace-nowrap {{ $activeTab === 'breakdown' ? 'border-purple-600 text-purple-700 dark:text-purple-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}"
        >
            <span>All Rules ({{ $scan->results->count() }})</span>
        </button>

        <button 
            wire:click="setTab('security')" 
            class="py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold border-b-2 transition flex items-center space-x-2 whitespace-nowrap {{ $activeTab === 'security' ? 'border-purple-600 text-purple-700 dark:text-purple-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}"
        >
            <span>🛡️ Security & Privacy</span>
            @if($failedSecurity->count() > 0)
                <span class="px-2 py-0.5 text-xs rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-800 dark:text-rose-200 font-bold border border-rose-200 dark:border-rose-800">
                    {{ $failedSecurity->count() }} Alert
                </span>
            @else
                <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold border border-emerald-200 dark:border-emerald-800">
                    Clean
                </span>
            @endif
        </button>

        <button 
            wire:click="setTab('keywords')" 
            class="py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold border-b-2 transition flex items-center space-x-2 whitespace-nowrap {{ $activeTab === 'keywords' ? 'border-purple-600 text-purple-700 dark:text-purple-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}"
        >
            <span>Target Keyword Match</span>
            @if(!empty($keywordMeta['missing_keywords']))
                <span class="px-2 py-0.5 text-xs rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-800 dark:text-rose-200 font-bold border border-rose-200 dark:border-rose-800">
                    {{ count($keywordMeta['missing_keywords']) }} Missing
                </span>
            @endif
        </button>

        <button 
            wire:click="setTab('plaintext')" 
            class="py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold border-b-2 transition flex items-center space-x-2 whitespace-nowrap {{ $activeTab === 'plaintext' ? 'border-purple-600 text-purple-700 dark:text-purple-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}"
        >
            <span>Parsed Plain-Text</span>
        </button>
    </div>

    <!-- Tab 1: Detailed Rule Breakdown -->
    @if($activeTab === 'breakdown')
        <div class="space-y-4">
            @foreach($scan->results as $res)
                <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-2xl p-6 shadow-md shadow-purple-500/5 flex flex-col md:flex-row md:items-start justify-between gap-4 transition-colors">
                    <div class="space-y-2 flex-grow">
                        <div class="flex items-center space-x-3">
                            @if($res->passed)
                                <span class="w-7 h-7 rounded-lg bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 flex items-center justify-center text-sm font-bold">✓</span>
                            @else
                                <span class="w-7 h-7 rounded-lg bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 flex items-center justify-center text-sm font-bold">✕</span>
                            @endif
                            
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ $res->rule_name }}</h4>
                            <span class="text-xs uppercase font-extrabold tracking-wider px-2.5 py-0.5 rounded bg-purple-50 dark:bg-slate-800 text-purple-800 dark:text-purple-300 border border-purple-100 dark:border-slate-700">
                                {{ $res->category }}
                            </span>
                        </div>

                        <p class="text-sm text-slate-600 dark:text-slate-300 pl-10 font-medium">{{ $res->message }}</p>

                        @if($res->recommendation)
                            <div class="ml-10 mt-3 p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 text-xs text-amber-900 dark:text-amber-200 flex items-start space-x-2 font-medium">
                                <span class="text-amber-700 dark:text-amber-400 font-bold shrink-0">💡 Recommendation:</span>
                                <span>{{ $res->recommendation }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-right shrink-0 md:pl-6 border-t md:border-t-0 md:border-l border-purple-100 dark:border-slate-800 pt-3 md:pt-0">
                        <span class="text-lg font-black font-mono {{ $res->passed ? 'text-purple-700 dark:text-purple-400' : 'text-rose-600 dark:text-rose-400' }}">
                            +{{ $res->score_awarded }}
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">/ {{ $res->max_score }} pts</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Tab 2: Security & Privacy Checkers -->
    @if($activeTab === 'security')
        <div class="space-y-4">
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 shadow-xl shadow-purple-500/5 mb-6 transition-colors">
                <div class="flex items-center space-x-3 mb-2">
                    <span class="text-2xl">🛡️</span>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Enterprise ATS Security & Privacy Scanner</h3>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    Leading enterprise recruiting engines automatically filter resumes for PII leaks, adversarial prompt injection payloads, leaked API keys, and active script attacks.
                </p>
            </div>

            @forelse($securityResults as $sec)
                <div class="bg-white dark:bg-slate-900 border {{ $sec->passed ? 'border-purple-100 dark:border-slate-800' : 'border-rose-200 dark:border-rose-900/60' }} rounded-2xl p-6 shadow-md shadow-purple-500/5 flex flex-col md:flex-row md:items-start justify-between gap-4 transition-colors">
                    <div class="space-y-2 flex-grow">
                        <div class="flex items-center space-x-3">
                            @if($sec->passed)
                                <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center text-sm font-bold">✓</span>
                            @else
                                <span class="w-7 h-7 rounded-lg bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 flex items-center justify-center text-sm font-bold">✕</span>
                            @endif
                            
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ $sec->rule_name }}</h4>
                            <span class="text-xs uppercase font-extrabold tracking-wider px-2.5 py-0.5 rounded {{ $sec->passed ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800' }}">
                                {{ $sec->passed ? 'Passed Security Check' : 'Security Red Flag' }}
                            </span>
                        </div>

                        <p class="text-sm text-slate-600 dark:text-slate-300 pl-10 font-medium">{{ $sec->message }}</p>

                        @if($sec->recommendation)
                            <div class="ml-10 mt-3 p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 text-xs text-amber-900 dark:text-amber-200 flex items-start space-x-2 font-medium">
                                <span class="text-amber-700 dark:text-amber-400 font-bold shrink-0">🛡️ Remediation Advice:</span>
                                <span>{{ $sec->recommendation }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-right shrink-0 md:pl-6 border-t md:border-t-0 md:border-l border-purple-100 dark:border-slate-800 pt-3 md:pt-0">
                        <span class="text-lg font-black font-mono {{ $sec->passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            +{{ $sec->score_awarded }}
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">/ {{ $sec->max_score }} pts</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-8 text-slate-500 dark:text-slate-400">
                    No security check records attached to this scan. Scan a fresh CV to evaluate against security checkers.
                </div>
            @endforelse
        </div>
    @endif

    <!-- Tab 3: Target Keyword Match Matrix -->
    @if($activeTab === 'keywords')
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-8 space-y-8 shadow-xl shadow-purple-500/5 transition-colors">
            @if(empty($scan->job_description))
                <div class="text-center py-12 space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-950 flex items-center justify-center mx-auto text-purple-700 dark:text-purple-300 text-2xl">🎯</div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">No Job Description Provided</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                        To see matched vs. missing target skills, scan your resume with a specific job description pasted.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Matched Keywords -->
                    <div class="bg-purple-50/50 dark:bg-slate-800/60 border border-purple-200/80 dark:border-slate-700 rounded-2xl p-6 space-y-4">
                        <h4 class="text-sm font-extrabold text-purple-900 dark:text-purple-300 uppercase tracking-wider flex items-center space-x-2">
                            <span>✓ Matched Target Keywords</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-purple-200 dark:bg-purple-900 text-purple-900 dark:text-purple-100 text-xs font-mono font-bold">
                                {{ count($keywordMeta['matched_keywords'] ?? []) }}
                            </span>
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse(($keywordMeta['matched_keywords'] ?? []) as $kw)
                                <span class="px-3.5 py-1.5 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-900 dark:text-purple-200 border border-purple-200 dark:border-purple-800 text-xs font-bold">
                                    {{ $kw }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-500 dark:text-slate-400 italic">No direct matches found.</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Missing Keywords -->
                    <div class="bg-rose-50/50 dark:bg-slate-800/60 border border-rose-200/80 dark:border-slate-700 rounded-2xl p-6 space-y-4">
                        <h4 class="text-sm font-extrabold text-rose-900 dark:text-rose-300 uppercase tracking-wider flex items-center space-x-2">
                            <span>✕ Missing Target Skills / Terms</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-rose-200 dark:bg-rose-900 text-rose-900 dark:text-rose-100 text-xs font-mono font-bold">
                                {{ count($keywordMeta['missing_keywords'] ?? []) }}
                            </span>
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse(($keywordMeta['missing_keywords'] ?? []) as $kw)
                                <span class="px-3.5 py-1.5 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-900 dark:text-rose-200 border border-rose-200 dark:border-rose-800 text-xs font-bold">
                                    + {{ $kw }}
                                </span>
                            @empty
                                <span class="text-xs text-purple-700 dark:text-purple-300 italic">All key terms present in your resume!</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Tab 4: Parsed Plain Text View -->
    @if($activeTab === 'plaintext')
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 space-y-4 shadow-xl shadow-purple-500/5 transition-colors">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">ATS Raw Extracted Text View</h3>
                <span class="text-xs text-slate-500 dark:text-slate-400">This is what automated recruiting systems "see" when parsing your CV</span>
            </div>
            <pre class="p-6 rounded-2xl bg-slate-900 dark:bg-slate-950 border border-slate-800 text-xs text-slate-200 font-mono whitespace-pre-wrap leading-relaxed overflow-x-auto max-h-[500px]">{{ $scan->raw_text }}</pre>
        </div>
    @endif

</div>
