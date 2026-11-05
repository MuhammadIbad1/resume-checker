<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-10">
        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800 mb-4 shadow-sm">
            ✨ AI-Powered ATS Resume & Security Audit
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            Check Your Resume Against ATS Filters & Security Rules
        </h1>
        <p class="mt-3 text-slate-600 dark:text-slate-400 max-w-xl mx-auto text-sm sm:text-base">
            Upload your resume PDF to test formatting, contact completeness, action verbs, metrics, targeted job keyword match, and enterprise security & privacy checks.
        </p>

        <!-- Usage meter badge -->
        <div class="mt-5 inline-flex items-center space-x-2 text-xs font-semibold px-4 py-2 rounded-xl bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 shadow-sm transition-colors">
            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span>Scans used this month: <strong class="text-purple-700 dark:text-purple-400 font-bold">{{ $scansUsed }}</strong> / {{ $scanLimit === -1 ? 'Unlimited' : $scanLimit }}</span>
        </div>
    </div>

    <!-- Main Scanner Form Card -->
    <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl shadow-purple-500/5 transition-colors">
        <form wire:submit="processScan" class="space-y-8">
            
            <!-- PDF Upload Zone -->
            <div>
                <label class="block text-sm font-bold text-slate-800 dark:text-slate-200 mb-3">
                    1. Upload Resume File (PDF) <span class="text-rose-500">*</span>
                </label>

                <div 
                    x-data="{ isDragging: false }"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="isDragging = false"
                    :class="isDragging ? 'border-purple-600 bg-purple-50 dark:bg-slate-800 scale-[1.01]' : 'border-purple-200 dark:border-slate-700 bg-purple-50/30 dark:bg-slate-800/40 hover:border-purple-400 dark:hover:border-purple-500'"
                    class="relative border-2 border-dashed rounded-2xl p-8 sm:p-12 text-center transition-all cursor-pointer group"
                >
                    <input 
                        type="file" 
                        wire:model="resumeFile" 
                        accept=".pdf"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    >

                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-purple-500/25 group-hover:scale-110 transition-transform">
                            @if($resumeFile)
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            @endif
                        </div>

                        <div>
                            @if($resumeFile)
                                <p class="text-base font-bold text-purple-900 dark:text-purple-300">{{ $resumeFile->getClientOriginalName() }}</p>
                                <p class="text-xs text-purple-700 dark:text-purple-400 font-medium mt-1">File attached & verified ({{ round($resumeFile->getSize() / 1024) }} KB)</p>
                            @else
                                <p class="text-base font-bold text-slate-800 dark:text-slate-200">
                                    Drop your resume PDF here, or <span class="text-purple-600 dark:text-purple-400 underline">browse file</span>
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Supports PDF files up to 5MB (magic byte verified)</p>
                            @endif
                        </div>
                    </div>
                </div>

                @error('resumeFile') <span class="text-xs text-rose-600 dark:text-rose-400 mt-2 block font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Job Description Optional Textarea -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-bold text-slate-800 dark:text-slate-200">
                        2. Target Job Description <span class="text-xs text-slate-500 dark:text-slate-400 font-normal">(Optional for Keyword Match)</span>
                    </label>
                    <span class="text-xs text-purple-700 dark:text-purple-300 font-bold bg-purple-100 dark:bg-purple-950 px-2.5 py-0.5 rounded-full border border-purple-200 dark:border-purple-800">Pro AI Matcher</span>
                </div>
                <textarea 
                    wire:model="jobDescription" 
                    rows="6"
                    placeholder="Paste the job description or role requirements here to check keyword match percentage, missing technical skills, and density..."
                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:border-purple-600 dark:focus:border-purple-400 focus:ring-2 focus:ring-purple-600/20 transition"
                ></textarea>
                @error('jobDescription') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button with Loading Indicator -->
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="w-full py-4 px-6 rounded-2xl font-extrabold text-base bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-600 text-white hover:opacity-95 transition shadow-xl shadow-purple-500/25 flex items-center justify-center space-x-3 disabled:opacity-50"
            >
                <span wire:loading.remove class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span>Run AI Resume Audit & Security Checks</span>
                </span>
                
                <span wire:loading class="flex items-center space-x-3">
                    <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Parsing PDF & Evaluating 6 Rule & Security Categories...</span>
                </span>
            </button>

        </form>
    </div>
</div>
