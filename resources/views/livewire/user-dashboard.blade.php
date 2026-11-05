<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    
    <!-- Top Welcome Banner -->
    <div class="bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 border border-purple-800 dark:border-purple-700 rounded-3xl p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl text-white relative overflow-hidden">
        <div class="space-y-2 z-10">
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white border border-white/30 uppercase tracking-wider">
                    Plan: {{ $plan->name ?? 'Free Starter' }}
                </span>
                <span class="text-xs text-purple-200">Account ID: #{{ $user->id }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white">Welcome back, {{ $user->name }}!</h1>
            <p class="text-sm text-purple-100 max-w-xl">
                Track your resume ATS optimization history, inspect detailed category breakdowns & security checkers, and upgrade plan limits anytime.
            </p>
        </div>

        <div class="flex items-center space-x-4 z-10">
            <a href="{{ route('scanner') }}" class="px-6 py-3.5 rounded-2xl font-bold text-sm bg-white text-purple-900 hover:bg-purple-50 transition shadow-lg flex items-center space-x-2">
                <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Upload & Scan Resume</span>
            </a>
        </div>
    </div>

    <!-- Stats Counter Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-2xl p-6 shadow-md shadow-purple-500/5 transition-colors">
            <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400 tracking-wider">Scans Used This Month</p>
            <div class="flex items-baseline space-x-2 mt-2">
                <span class="text-3xl font-black text-purple-700 dark:text-purple-400 font-mono">{{ $scansThisMonth }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">/ {{ $plan->scan_limit === -1 ? 'Unlimited' : $plan->scan_limit }} limit</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-2xl p-6 shadow-md shadow-purple-500/5 transition-colors">
            <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400 tracking-wider">Job Match Density Limit</p>
            <div class="flex items-baseline space-x-2 mt-2">
                <span class="text-3xl font-black text-indigo-700 dark:text-indigo-400 font-mono">
                    {{ $plan->jd_limit === -1 ? 'Unlimited' : $plan->jd_limit }}
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">matches / month</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-2xl p-6 shadow-md shadow-purple-500/5 transition-colors">
            <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400 tracking-wider">Total Resumes Scanned</p>
            <div class="flex items-baseline space-x-2 mt-2">
                <span class="text-3xl font-black text-purple-900 dark:text-purple-300 font-mono">{{ $user->scans()->count() }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">historical reports</span>
            </div>
        </div>
    </div>

    <!-- Scan History Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl shadow-purple-500/5 transition-colors">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Recent Scan History</h3>
            <a href="{{ route('scanner') }}" class="text-xs font-bold text-purple-700 dark:text-purple-400 hover:underline">+ New Scan</a>
        </div>

        @if($scans->isEmpty())
            <div class="text-center py-12 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-950 flex items-center justify-center mx-auto text-2xl text-purple-700 dark:text-purple-300">📄</div>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">No resumes scanned yet</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Upload your first PDF resume to run an automated 6-category ATS rule & security evaluation.</p>
                <a href="{{ route('scanner') }}" class="inline-block px-5 py-2.5 rounded-xl font-bold text-xs bg-purple-600 text-white hover:bg-purple-700 transition">
                    Scan Resume Now
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="text-xs uppercase bg-purple-50/60 dark:bg-slate-800/60 text-purple-900 dark:text-purple-200 border-b border-purple-100 dark:border-slate-800 font-bold">
                        <tr>
                            <th class="px-4 py-3">File Name</th>
                            <th class="px-4 py-3">Scan Date</th>
                            <th class="px-4 py-3">ATS Score</th>
                            <th class="px-4 py-3">Job Match</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-100 dark:divide-slate-800">
                        @foreach($scans as $scanItem)
                            @php
                                $scoreVal = $scanItem->overall_score;
                                $scoreBadge = $scoreVal >= 80 
                                    ? 'bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border-purple-200 dark:border-purple-800' 
                                    : ($scoreVal >= 60 
                                        ? 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800' 
                                        : 'bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-800');
                            @endphp
                            <tr class="hover:bg-purple-50/40 dark:hover:bg-slate-800/40 transition">
                                <td class="px-4 py-4 font-bold text-slate-900 dark:text-white flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    <span class="truncate max-w-xs">{{ $scanItem->file_name }}</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 dark:text-slate-400 text-xs font-medium">
                                    {{ $scanItem->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold border {{ $scoreBadge }}">
                                        {{ $scoreVal }} / 100
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs">
                                    @if($scanItem->job_description)
                                        <span class="text-purple-700 dark:text-purple-400 font-bold">Matched JD</span>
                                    @else
                                        <span class="text-slate-400 font-medium">General Audit</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <a href="{{ route('scan.results', ['scan' => $scanItem->id]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-purple-50 dark:bg-purple-950/80 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800 hover:bg-purple-100 dark:hover:bg-purple-900/60 transition inline-flex items-center space-x-1">
                                        <span>View Report</span>
                                        <svg class="w-3 h-3 text-purple-700 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $scans->links() }}
            </div>
        @endif
    </div>
</div>
