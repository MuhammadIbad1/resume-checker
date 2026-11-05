<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $resume->personal_info['full_name'] ?? 'Resume' }} — Compact Clean Pro Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans p-3 sm:p-6 text-slate-800 text-[13px] leading-snug">

    <!-- Top Action Bar -->
    @include('resume-templates.partials.top_action_bar', ['templateName' => 'Compact Clean Pro', 'atsReport' => $atsReport ?? null])

    <!-- Compact Resume Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-10 border border-slate-300 shadow-xl rounded-lg space-y-4">
        
        <!-- Header (Compact Inline) -->
        <div class="border-b-2 border-emerald-600 pb-3 flex flex-col sm:flex-row justify-between items-baseline gap-2">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    {{ $resume->personal_info['full_name'] ?? 'YOUR NAME' }}
                </h1>
                <p class="text-sm font-bold text-emerald-700">
                    {{ $resume->personal_info['job_title'] ?? 'Professional Title' }}
                </p>
            </div>

            <div class="text-[11px] font-semibold text-slate-600 space-x-2 sm:text-right">
                @if(!empty($resume->personal_info['email'])) <span>{{ $resume->personal_info['email'] }}</span> • @endif
                @if(!empty($resume->personal_info['phone'])) <span>{{ $resume->personal_info['phone'] }}</span> • @endif
                @if(!empty($resume->personal_info['location'])) <span>{{ $resume->personal_info['location'] }}</span> • @endif
                @if(!empty($resume->personal_info['linkedin'])) <span>{{ $resume->personal_info['linkedin'] }}</span> @endif
            </div>
        </div>

        <!-- Summary -->
        @if(!empty($resume->personal_info['summary']))
            <div>
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-0.5 mb-1 text-emerald-800">
                    Summary
                </h2>
                <p class="text-xs text-slate-700 leading-relaxed">
                    {{ $resume->personal_info['summary'] }}
                </p>
            </div>
        @endif

        <!-- Skills (Compact Inline Pills) -->
        @if(!empty($resume->skills))
            <div>
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-0.5 mb-1 text-emerald-800">
                    Core Skills
                </h2>
                <div class="text-xs text-slate-700 space-y-0.5">
                    @foreach($resume->skills as $cat => $items)
                        <div>
                            @if(is_string($cat) && !is_numeric($cat))
                                <strong class="text-slate-900">{{ $cat }}:</strong>
                            @endif
                            <span>{{ is_array($items) ? implode(', ', $items) : $items }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Work Experience -->
        @if(!empty($resume->work_experience))
            <div>
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-0.5 mb-2 text-emerald-800">
                    Experience
                </h2>
                <div class="space-y-3">
                    @foreach($resume->work_experience as $exp)
                        <div>
                            <div class="flex justify-between items-baseline text-xs">
                                <div>
                                    <strong class="text-slate-900">{{ $exp['role'] ?? '' }}</strong>
                                    <span class="text-slate-600"> — {{ $exp['company'] ?? '' }}</span>
                                </div>
                                <span class="font-mono text-[11px] text-slate-500 font-semibold">
                                    {{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? 'Present' }}
                                </span>
                            </div>

                            @if(!empty($exp['bullets']))
                                <ul class="mt-1 space-y-0.5 text-xs text-slate-700 list-disc list-inside">
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

        <!-- Projects -->
        @if(!empty($resume->projects))
            <div>
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-0.5 mb-1 text-emerald-800">
                    Key Projects
                </h2>
                <div class="space-y-2 text-xs">
                    @foreach($resume->projects as $proj)
                        <div>
                            <div class="flex justify-between items-baseline">
                                <strong class="text-slate-900">{{ $proj['title'] ?? '' }}</strong>
                                @if(!empty($proj['role'])) <span class="text-emerald-700 text-[11px] font-medium">{{ $proj['role'] }}</span> @endif
                            </div>
                            @if(!empty($proj['description'])) <p class="text-slate-600 text-[11px]">{{ $proj['description'] }}</p> @endif
                            @if(!empty($proj['bullets']))
                                <ul class="mt-0.5 space-y-0.5 list-disc list-inside text-slate-700">
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
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-0.5 mb-1 text-emerald-800">
                    Education
                </h2>
                <div class="space-y-1 text-xs">
                    @foreach($resume->education as $edu)
                        <div class="flex justify-between items-baseline">
                            <div>
                                <strong class="text-slate-900">{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</strong>
                                <span class="text-slate-600"> — {{ $edu['institution'] ?? '' }}</span>
                            </div>
                            <span class="font-mono text-[11px] text-slate-500 font-semibold">{{ $edu['year'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Certifications -->
        @if(!empty($resume->certifications))
            <div>
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-0.5 mb-1 text-emerald-800">
                    Certifications
                </h2>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-700">
                    @foreach($resume->certifications as $cert)
                        <div>
                            <strong class="text-slate-900">{{ $cert['name'] ?? '' }}</strong>
                            @if(!empty($cert['issuer'])) <span class="text-slate-500">({{ $cert['issuer'] }})</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Custom Additional Sections -->
        @if(!empty($resume->custom_sections))
            @foreach($resume->custom_sections as $cSec)
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-0.5 mb-1 text-emerald-800">
                        {{ strtoupper($cSec['title'] ?? 'ADDITIONAL INFO') }}
                    </h2>
                    @if(!empty($cSec['content']))
                        <p class="text-xs text-slate-700 mb-1">{{ $cSec['content'] }}</p>
                    @endif
                    @if(!empty($cSec['items']))
                        <ul class="space-y-0.5 text-xs text-slate-700 list-disc list-inside">
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

