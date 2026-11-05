<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    
    <!-- Top Banner -->
    <div class="border-b border-purple-100 dark:border-slate-800 pb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 transition-colors">
        <div>
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800 mb-3 shadow-sm">
                📑 ATS Resume Generator
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">My ATS Resumes</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                Create, edit, and export ATS-optimized resumes with 4 professional templates.
            </p>
        </div>

        <a href="{{ route('builder.create') }}" class="px-6 py-3.5 rounded-2xl font-bold text-sm bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 transition shadow-lg shadow-purple-500/25 flex items-center space-x-2">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>+ Create New ATS Resume</span>
        </a>
    </div>

    <!-- Resume Cards Grid -->
    @if($resumes->isEmpty())
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-12 text-center space-y-4 shadow-xl shadow-purple-500/5 max-w-xl mx-auto transition-colors">
            <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-950 flex items-center justify-center mx-auto text-2xl text-purple-700 dark:text-purple-300">📑</div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">No Built Resumes Yet</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Use our interactive ATS Resume Builder to generate a clean, ATS-compliant CV in under 3 minutes.
            </p>
            <a href="{{ route('builder.create') }}" class="inline-block px-6 py-3 rounded-xl font-bold text-sm bg-purple-600 text-white hover:bg-purple-700 transition">
                Build First ATS Resume
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($resumes as $res)
                @php $ats = $res->ats_score; @endphp
                <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 shadow-xl shadow-purple-500/5 flex flex-col justify-between space-y-6 hover:border-purple-300 dark:hover:border-purple-600 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase bg-purple-50 dark:bg-purple-950/80 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                {{ str_replace('_', ' ', $res->template_slug) }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $ats['badgeColor'] }}">
                                {{ $ats['icon'] }} {{ $ats['score'] }}% ATS Friendly
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $res->title }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">
                            {{ $res->personal_info['full_name'] ?? 'Candidate' }} — {{ $res->personal_info['job_title'] ?? 'Role' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-purple-100 dark:border-slate-800 space-y-2">
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('builder.preview', ['userResume' => $res->id]) }}" target="_blank" class="py-2.5 px-3 rounded-xl text-center text-xs font-bold bg-purple-50 dark:bg-slate-800 text-purple-900 dark:text-purple-300 border border-purple-200 dark:border-slate-700 hover:bg-purple-100 dark:hover:bg-slate-700 transition">
                                👁️ View & Print
                            </a>

                            <a href="{{ route('builder.edit', ['userResume' => $res->id]) }}" class="py-2.5 px-3 rounded-xl text-center text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                ✏️ Edit Details
                            </a>
                        </div>

                        <button 
                            wire:click="auditResume({{ $res->id }})" 
                            class="w-full py-2.5 px-3 rounded-xl text-center text-xs font-extrabold bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 shadow-md shadow-purple-500/20 transition flex items-center justify-center space-x-1.5"
                        >
                            <span>⚡ Audit Score & Security Check</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
