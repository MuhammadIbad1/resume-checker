<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $resume->personal_info['full_name'] ?? 'Resume' }} — Tech Innovator Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans p-4 sm:p-8 text-slate-800">

    <!-- Top Action Bar (Includes Live ATS Friendliness Audit) -->
    @include('resume-templates.partials.top_action_bar', ['templateName' => 'Tech Innovator', 'atsReport' => $atsReport ?? null])

    <!-- Resume Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 border border-slate-200 shadow-xl rounded-xl space-y-7">
        
        <!-- Header -->
        <div class="border-b-2 border-indigo-600 pb-5 flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-mono">
                    {{ $resume->personal_info['full_name'] ?? 'YOUR NAME' }}
                </h1>
                <p class="text-base font-bold text-indigo-600 mt-1">
                    {{ $resume->personal_info['job_title'] ?? 'Full Stack Software Engineer' }}
                </p>
            </div>

            <div class="text-xs font-mono font-medium text-slate-600 space-y-1 sm:text-right">
                @if(!empty($resume->personal_info['email'])) <div><span class="text-indigo-600 font-bold">&gt;</span> {{ $resume->personal_info['email'] }}</div> @endif
                @if(!empty($resume->personal_info['phone'])) <div><span class="text-indigo-600 font-bold">&gt;</span> {{ $resume->personal_info['phone'] }}</div> @endif
                @if(!empty($resume->personal_info['location'])) <div><span class="text-indigo-600 font-bold">&gt;</span> {{ $resume->personal_info['location'] }}</div> @endif
                @if(!empty($resume->personal_info['linkedin'])) <div><span class="text-indigo-600 font-bold">&gt;</span> {{ $resume->personal_info['linkedin'] }}</div> @endif
                @if(!empty($resume->personal_info['github'])) <div><span class="text-indigo-600 font-bold">&gt;</span> {{ $resume->personal_info['github'] }}</div> @endif
            </div>
        </div>

        <!-- Summary -->
        @if(!empty($resume->personal_info['summary']))
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-900 border-b border-indigo-100 pb-1 mb-2 font-mono flex items-center space-x-2">
                    <span class="text-indigo-600">//</span> <span>EXECUTIVE SUMMARY</span>
                </h2>
                <p class="text-xs text-slate-700 leading-relaxed font-medium">
                    {{ $resume->personal_info['summary'] }}
                </p>
            </div>
        @endif

        <!-- Technical Skills Matrix -->
        @if(!empty($resume->skills))
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-900 border-b border-indigo-100 pb-1 mb-3 font-mono flex items-center space-x-2">
                    <span class="text-indigo-600">//</span> <span>CORE TECHNICAL STACK</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach($resume->skills as $cat => $items)
                        <div class="p-2.5 rounded-lg bg-indigo-50/50 border border-indigo-100">
                            @if(is_string($cat) && !is_numeric($cat))
                                <span class="font-bold text-indigo-950 font-mono block text-[11px] mb-0.5">{{ $cat }}:</span>
                            @endif
                            <span class="text-slate-700 font-medium">{{ is_array($items) ? implode(', ', $items) : $items }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Work Experience -->
        @if(!empty($resume->work_experience))
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-900 border-b border-indigo-100 pb-1 mb-4 font-mono flex items-center space-x-2">
                    <span class="text-indigo-600">//</span> <span>WORK EXPERIENCE</span>
                </h2>
                <div class="space-y-5">
                    @foreach($resume->work_experience as $exp)
                        <div>
                            <div class="flex justify-between items-baseline">
                                <h3 class="text-sm font-bold text-slate-900 font-mono">
                                    {{ $exp['role'] ?? '' }} <span class="text-indigo-600">@</span> <span class="font-normal text-slate-700">{{ $exp['company'] ?? '' }}</span>
                                </h3>
                                <span class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? 'Present' }}
                                </span>
                            </div>

                            @if(!empty($exp['bullets']))
                                <ul class="mt-2 space-y-1.5 text-xs text-slate-700 list-disc list-inside font-medium">
                                    @foreach(array_filter(explode("\n", $exp['bullets'])) as $b)
                                        <li>{{ trim($b) }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Key Projects -->
        @if(!empty($resume->projects))
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-900 border-b border-indigo-100 pb-1 mb-3 font-mono flex items-center space-x-2">
                    <span class="text-indigo-600">//</span> <span>KEY TECHNICAL PROJECTS</span>
                </h2>
                <div class="space-y-4">
                    @foreach($resume->projects as $proj)
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                            <div class="flex justify-between items-baseline text-xs">
                                <span class="font-bold text-slate-900 font-mono">{{ $proj['title'] ?? '' }}</span>
                                @if(!empty($proj['link']))
                                    <span class="text-[11px] text-indigo-600 font-mono">{{ $proj['link'] }}</span>
                                @endif
                            </div>
                            @if(!empty($proj['role']))
                                <p class="text-[11px] text-indigo-700 font-medium mt-0.5">Role / Tech: {{ $proj['role'] }}</p>
                            @endif
                            @if(!empty($proj['description']))
                                <p class="text-xs text-slate-600 mt-1">{{ $proj['description'] }}</p>
                            @endif
                            @if(!empty($proj['bullets']))
                                <ul class="mt-1 space-y-0.5 text-xs text-slate-700 list-disc list-inside">
                                    @foreach(array_filter(explode("\n", $proj['bullets'])) as $pb)
                                        <li>{{ trim($pb) }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Education -->
        @if(!empty($resume->education))
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-900 border-b border-indigo-100 pb-1 mb-3 font-mono flex items-center space-x-2">
                    <span class="text-indigo-600">//</span> <span>EDUCATION</span>
                </h2>
                <div class="space-y-2">
                    @foreach($resume->education as $edu)
                        <div class="flex justify-between items-baseline text-xs">
                            <div>
                                <span class="font-bold text-slate-900 font-mono">{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</span>
                                <span class="text-slate-600"> — {{ $edu['institution'] ?? '' }}</span>
                            </div>
                            <span class="font-mono text-slate-500 font-bold">{{ $edu['year'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Certifications -->
        @if(!empty($resume->certifications))
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-900 border-b border-indigo-100 pb-1 mb-2 font-mono flex items-center space-x-2">
                    <span class="text-indigo-600">//</span> <span>CERTIFICATIONS & LICENSES</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach($resume->certifications as $cert)
                        <div class="p-2 rounded bg-slate-50 border border-slate-200">
                            <span class="font-bold text-slate-900">{{ $cert['name'] ?? '' }}</span>
                            @if(!empty($cert['issuer'])) <span class="text-slate-500 text-[11px]"> — {{ $cert['issuer'] }}</span> @endif
                            @if(!empty($cert['year'])) <span class="text-slate-400 text-[10px] font-mono block">{{ $cert['year'] }}</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Custom Additional Sections -->
        @if(!empty($resume->custom_sections))
            @foreach($resume->custom_sections as $cSec)
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-900 border-b border-indigo-100 pb-1 mb-2 font-mono flex items-center space-x-2">
                        <span class="text-indigo-600">//</span> <span>{{ strtoupper($cSec['title'] ?? 'ADDITIONAL SECTION') }}</span>
                    </h2>
                    @if(!empty($cSec['content']))
                        <p class="text-xs text-slate-700 leading-relaxed font-medium mb-2">{{ $cSec['content'] }}</p>
                    @endif
                    @if(!empty($cSec['items']))
                        <ul class="space-y-1 text-xs text-slate-700 list-disc list-inside font-medium">
                            @foreach(array_filter(explode("\n", $cSec['items'])) as $it)
                                <li>{{ trim($it) }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        @endif

    </div>

</body>
</html>

