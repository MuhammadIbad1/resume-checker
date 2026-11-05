<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    
    <!-- Top Header -->
    <div class="border-b border-purple-100 dark:border-slate-800 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('resumes.index') }}" class="text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 transition-colors">← My Resumes</a>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                {{ $userResume ? 'Edit ATS Resume' : 'ATS Resume Builder & Generator' }}
            </h1>
        </div>

        <button 
            wire:click="saveResume" 
            class="px-6 py-3.5 rounded-2xl font-bold text-sm bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 shadow-lg shadow-purple-500/25 flex items-center space-x-2 transition"
        >
            <span>💾 Save & Generate Printable CV</span>
        </button>
    </div>

    <!-- Real-Time ATS Friendliness Score Bar -->
    <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 shadow-xl shadow-purple-500/5 transition-colors">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex flex-col items-center justify-center font-mono font-black shadow-lg shadow-purple-500/25 shrink-0">
                    <span class="text-2xl leading-none">{{ $atsReport['score'] }}</span>
                    <span class="text-[9px] uppercase font-bold text-purple-200">/ 100</span>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold border {{ $atsReport['badgeColor'] }}">
                            {{ $atsReport['icon'] }} {{ $atsReport['grade'] }}
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Live ATS Engine Audit</span>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                        ATS Friendliness & Auto-Rejection Defense
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ $atsReport['totalSections'] }} sections detected. Score updates automatically as you type and add sections.
                    </p>
                </div>
            </div>

            <!-- Progress Meter -->
            <div class="md:w-72 space-y-2">
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-700 dark:text-slate-300">Resume ATS Rating</span>
                    <span class="text-purple-700 dark:text-purple-400 font-mono font-extrabold">{{ $atsReport['score'] }}%</span>
                </div>
                <div class="w-full h-3 bg-purple-50 dark:bg-slate-800 rounded-full overflow-hidden border border-purple-100 dark:border-slate-700 p-0.5">
                    <div class="h-full bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full transition-all duration-500" style="width: {{ $atsReport['score'] }}%"></div>
                </div>
                @if(!empty($atsReport['tips']))
                    <p class="text-[11px] text-purple-700 dark:text-purple-400 truncate">
                        💡 <strong>Tip:</strong> {{ $atsReport['tips'][0] }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <form wire:submit="saveResume" class="space-y-10">

        <!-- 1. Select Template (8 Multiple Templates) -->
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl shadow-purple-500/5 transition-colors">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xs font-bold">1</span>
                    <span>Choose Resume Template (8 ATS-Compliant Layouts)</span>
                </h3>
                <span class="text-xs font-bold text-purple-700 dark:text-purple-400">Selected: {{ str_replace('_', ' ', ucfirst($template_slug)) }}</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @php
                    $templates = [
                        ['slug' => 'modern_ats', 'name' => 'Modern ATS', 'desc' => 'Purple headers, clean dual typography', 'icon' => '💜'],
                        ['slug' => 'classic_clean', 'name' => 'Classic Corporate', 'desc' => 'Traditional serif style, solid rules', 'icon' => '🏛️'],
                        ['slug' => 'minimal_tech', 'name' => 'Minimal Tech', 'desc' => 'Monospace tech stack tags & bullets', 'icon' => '⚡'],
                        ['slug' => 'executive_pro', 'name' => 'Executive Pro', 'desc' => 'Dark summary header box, compact grid', 'icon' => '💼'],
                        ['slug' => 'tech_innovator', 'name' => 'Tech Innovator', 'desc' => 'Terminal code style, project cards, cloud/dev', 'icon' => '🚀'],
                        ['slug' => 'ivy_academic', 'name' => 'Ivy Academic', 'desc' => 'Prestigious serif, academic/legal/research', 'icon' => '🎓'],
                        ['slug' => 'compact_clean', 'name' => 'Compact 1-Page', 'desc' => 'Space-optimized single-page pro layout', 'icon' => '📐'],
                        ['slug' => 'nordic_minimal', 'name' => 'Nordic Minimal', 'desc' => 'Swiss typography, numbered section headers', 'icon' => '❄️'],
                    ];
                @endphp

                @foreach($templates as $tpl)
                    <div 
                        wire:click="$set('template_slug', '{{ $tpl['slug'] }}')"
                        class="p-4 rounded-2xl border-2 cursor-pointer transition text-left space-y-2 {{ $template_slug === $tpl['slug'] ? 'border-purple-600 dark:border-purple-500 bg-purple-50/70 dark:bg-purple-950/50 shadow-md ring-2 ring-purple-500/20' : 'border-purple-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 hover:border-purple-300 dark:hover:border-slate-700' }}"
                    >
                        <span class="text-2xl">{{ $tpl['icon'] }}</span>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $tpl['name'] }}</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-tight">{{ $tpl['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2. Personal Information & Summary -->
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl shadow-purple-500/5 transition-colors">
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center space-x-2">
                <span class="w-7 h-7 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xs font-bold">2</span>
                <span>Personal & Contact Information</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Resume Document Title</label>
                    <input wire:model.live="title" type="text" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Full Candidate Name <span class="text-rose-500">*</span></label>
                    <input wire:model.live="fullName" type="text" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Target Professional Title</label>
                    <input wire:model.live="jobTitle" type="text" placeholder="e.g. Senior Full Stack Engineer" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address <span class="text-rose-500">*</span></label>
                    <input wire:model.live="email" type="email" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                    <input wire:model.live="phone" type="text" placeholder="+1 (555) 019-2834" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Location (City, Country)</label>
                    <input wire:model.live="location" type="text" placeholder="San Francisco, CA (Remote)" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">LinkedIn Profile Link</label>
                    <input wire:model.live="linkedin" type="text" placeholder="linkedin.com/in/username" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">GitHub / Portfolio URL</label>
                    <input wire:model.live="github" type="text" placeholder="github.com/username" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Professional Executive Summary</label>
                <textarea wire:model.live="summary" rows="3" placeholder="Brief 2-3 sentence overview highlighting your career track record, top technical skills, and key accomplishments..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600"></textarea>
            </div>
        </div>

        <!-- 3. Work Experience -->
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl shadow-purple-500/5 transition-colors">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xs font-bold">3</span>
                    <span>Work Experience & Achievements</span>
                </h3>
                <button type="button" wire:click="addExperience" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 hover:bg-purple-200 transition">
                    + Add Job Role
                </button>
            </div>

            <div class="space-y-6">
                @foreach($workExperience as $idx => $exp)
                    <div class="p-5 rounded-2xl bg-purple-50/40 dark:bg-slate-800/40 border border-purple-100 dark:border-slate-700 space-y-4 relative">
                        <button type="button" wire:click="removeExperience({{ $idx }})" class="absolute top-4 right-4 text-xs font-bold text-rose-600 hover:underline">
                            Remove
                        </button>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Job Role / Position</label>
                                <input wire:model.live="workExperience.{{ $idx }}.role" type="text" placeholder="e.g. Senior Software Engineer" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Company / Organization</label>
                                <input wire:model.live="workExperience.{{ $idx }}.company" type="text" placeholder="e.g. TechCorp Global" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Start Date / Year</label>
                                <input wire:model.live="workExperience.{{ $idx }}.start_date" type="text" placeholder="2022" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">End Date / Year</label>
                                <input wire:model.live="workExperience.{{ $idx }}.end_date" type="text" placeholder="Present or 2024" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Achievement Bullets <span class="text-purple-700 dark:text-purple-400 font-normal">(One bullet per line — use action verbs and numbers for high ATS score)</span>
                            </label>
                            <textarea wire:model.live="workExperience.{{ $idx }}.bullets" rows="3" placeholder="• Architected microservices API reducing latency by 42% for 200,000+ daily users...&#10;• Spearheaded team migration to Laravel 12 increasing query efficiency by 35%..." class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs"></textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 4. Key Projects Section -->
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl shadow-purple-500/5 transition-colors">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xs font-bold">4</span>
                    <span>Key Projects & Portfolio (Optional)</span>
                </h3>
                <button type="button" wire:click="addProject" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 hover:bg-purple-200 transition">
                    + Add Project
                </button>
            </div>

            <div class="space-y-4">
                @forelse($projects as $idx => $proj)
                    <div class="p-5 rounded-2xl bg-purple-50/30 dark:bg-slate-800/40 border border-purple-100 dark:border-slate-700 space-y-3 relative">
                        <button type="button" wire:click="removeProject({{ $idx }})" class="absolute top-4 right-4 text-xs font-bold text-rose-600 hover:underline">
                            Remove
                        </button>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Project Name</label>
                                <input wire:model.live="projects.{{ $idx }}.title" type="text" placeholder="e.g. ATS Pulse SaaS" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Role / Tech Stack</label>
                                <input wire:model.live="projects.{{ $idx }}.role" type="text" placeholder="e.g. Lead Architect (Laravel, Livewire)" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Demo / GitHub Link</label>
                                <input wire:model.live="projects.{{ $idx }}.link" type="text" placeholder="https://github.com/..." class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Project Description & Results</label>
                            <textarea wire:model.live="projects.{{ $idx }}.description" rows="2" placeholder="Brief description of the project problem, architecture, and measurable delivery..." class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs"></textarea>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic">No projects added yet. Click "+ Add Project" to highlight hands-on delivery.</p>
                @endforelse
            </div>
        </div>

        <!-- 5. Education -->
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl shadow-purple-500/5 transition-colors">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xs font-bold">5</span>
                    <span>Education & Qualifications</span>
                </h3>
                <button type="button" wire:click="addEducation" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 hover:bg-purple-200 transition">
                    + Add Degree
                </button>
            </div>

            <div class="space-y-4">
                @foreach($education as $idx => $edu)
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 grid grid-cols-1 sm:grid-cols-4 gap-3 relative">
                        <button type="button" wire:click="removeEducation({{ $idx }})" class="absolute top-2 right-2 text-[10px] font-bold text-rose-600">✕</button>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400">Degree</label>
                            <input wire:model.live="education.{{ $idx }}.degree" type="text" placeholder="Bachelor of Science" class="w-full px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white text-xs">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400">Field of Study</label>
                            <input wire:model.live="education.{{ $idx }}.field" type="text" placeholder="Computer Science" class="w-full px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white text-xs">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400">Institution</label>
                            <input wire:model.live="education.{{ $idx }}.institution" type="text" placeholder="UC Berkeley" class="w-full px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white text-xs">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400">Graduation Year</label>
                            <input wire:model.live="education.{{ $idx }}.year" type="text" placeholder="2019" class="w-full px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white text-xs">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 6. Certifications & Licenses -->
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl shadow-purple-500/5 transition-colors">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xs font-bold">6</span>
                    <span>Certifications & Credentials (Optional)</span>
                </h3>
                <button type="button" wire:click="addCertification" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 hover:bg-purple-200 transition">
                    + Add Certification
                </button>
            </div>

            <div class="space-y-3">
                @forelse($certifications as $idx => $cert)
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 grid grid-cols-1 sm:grid-cols-3 gap-3 relative">
                        <button type="button" wire:click="removeCertification({{ $idx }})" class="absolute top-2 right-2 text-[10px] font-bold text-rose-600">✕</button>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400">Certification Name</label>
                            <input wire:model.live="certifications.{{ $idx }}.name" type="text" placeholder="e.g. AWS Solutions Architect" class="w-full px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400">Issuing Body / Authority</label>
                            <input wire:model.live="certifications.{{ $idx }}.issuer" type="text" placeholder="e.g. Amazon Web Services" class="w-full px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400">Year / Validity</label>
                            <input wire:model.live="certifications.{{ $idx }}.year" type="text" placeholder="2023" class="w-full px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs">
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic">No certifications added. Click "+ Add Certification" to include AWS, Google, Scrum, or Cisco badges.</p>
                @endforelse
            </div>
        </div>

        <!-- 7. Custom Additional Sections (User can add MORE sections!) -->
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl shadow-purple-500/5 transition-colors">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center space-x-2">
                        <span class="w-7 h-7 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xs font-bold">7</span>
                        <span>Add Custom Additional Sections</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Add any custom section you need: Languages, Honors, Publications, Volunteer, etc.</p>
                </div>

                <!-- Quick Presets -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <button type="button" wire:click="addCustomSection('Languages & Fluency')" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 hover:bg-purple-100">
                        + Languages
                    </button>
                    <button type="button" wire:click="addCustomSection('Awards & Honors')" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 hover:bg-purple-100">
                        + Awards
                    </button>
                    <button type="button" wire:click="addCustomSection('Volunteer & Leadership')" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 hover:bg-purple-100">
                        + Volunteer
                    </button>
                    <button type="button" wire:click="addCustomSection('Custom Section')" class="px-3 py-1 rounded-lg text-xs font-bold bg-purple-600 text-white hover:bg-purple-700">
                        + Custom
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($customSections as $cIdx => $cSec)
                    <div class="p-5 rounded-2xl bg-purple-50/40 dark:bg-slate-800/40 border border-purple-100 dark:border-slate-700 space-y-3 relative">
                        <button type="button" wire:click="removeCustomSection({{ $cIdx }})" class="absolute top-4 right-4 text-xs font-bold text-rose-600 hover:underline">
                            Remove Section
                        </button>

                        <div class="max-w-xs">
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Section Title</label>
                            <input wire:model.live="customSections.{{ $cIdx }}.title" type="text" placeholder="e.g. Languages / Publications" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Section Content (Text or Paragraph)</label>
                            <textarea wire:model.live="customSections.{{ $cIdx }}.content" rows="2" placeholder="Brief paragraph or overview for this section..." class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Bullet Items (Optional, one per line)</label>
                            <textarea wire:model.live="customSections.{{ $cIdx }}.items" rows="2" placeholder="• Item 1&#10;• Item 2" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs"></textarea>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic">No custom sections added yet. Use the buttons above to add Languages, Awards, or custom topics.</p>
                @endforelse
            </div>
        </div>

        <!-- Submit CTA -->
        <div class="flex items-center justify-between p-6 bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl transition-colors">
            <div class="flex items-center space-x-3">
                <span class="text-2xl">{{ $atsReport['icon'] }}</span>
                <div>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Current ATS Friendliness</span>
                    <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $atsReport['grade'] }} ({{ $atsReport['score'] }}%)</h4>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('resumes.index') }}" class="px-5 py-3 rounded-2xl font-bold text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3.5 rounded-2xl font-extrabold text-sm bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 shadow-xl shadow-purple-500/25 flex items-center space-x-2">
                    <span>Save & Check ATS Friendliness →</span>
                </button>
            </div>
        </div>

    </form>
</div>
