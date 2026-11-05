<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $resume->personal_info['full_name'] ?? 'Resume' }} — Modern ATS Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans p-4 sm:p-8 text-slate-800">

    <!-- Top Action Bar (Includes Live ATS Friendliness Audit) -->
    @include('resume-templates.partials.top_action_bar', ['templateName' => 'Modern ATS', 'atsReport' => $atsReport ?? null])

    <!-- Resume Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 border border-slate-200 shadow-xl rounded-xl space-y-8">
        
        <!-- Header / Contact Information -->
        <div class="border-b-2 border-purple-600 pb-6 text-center sm:text-left flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    {{ $resume->personal_info['full_name'] ?? 'YOUR NAME' }}
                </h1>
                <p class="text-base font-bold text-purple-700 mt-1">
                    {{ $resume->personal_info['job_title'] ?? 'Professional Title' }}
                </p>
            </div>

            <div class="text-xs font-semibold text-slate-600 space-y-1 text-right">
                @if(!empty($resume->personal_info['email'])) <div>📧 {{ $resume->personal_info['email'] }}</div> @endif
                @if(!empty($resume->personal_info['phone'])) <div>📞 {{ $resume->personal_info['phone'] }}</div> @endif
                @if(!empty($resume->personal_info['location'])) <div>📍 {{ $resume->personal_info['location'] }}</div> @endif
                @if(!empty($resume->personal_info['linkedin'])) <div>🔗 {{ $resume->personal_info['linkedin'] }}</div> @endif
            </div>
        </div>

        <!-- Professional Summary -->
        @if(!empty($resume->personal_info['summary']))
            <div>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-purple-900 border-b border-purple-100 pb-1 mb-2">
                    Professional Summary
                </h2>
                <p class="text-xs text-slate-700 leading-relaxed font-medium">
                    {{ $resume->personal_info['summary'] }}
                </p>
            </div>
        @endif

        <!-- Work Experience -->
        @if(!empty($resume->work_experience))
            <div>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-purple-900 border-b border-purple-100 pb-1 mb-4">
                    Work Experience
                </h2>
                <div class="space-y-5">
                    @foreach($resume->work_experience as $exp)
                        <div>
                            <div class="flex justify-between items-baseline">
                                <h3 class="text-sm font-bold text-slate-900">
                                    {{ $exp['role'] ?? '' }} <span class="font-normal text-slate-600">at {{ $exp['company'] ?? '' }}</span>
                                </h3>
                                <span class="text-xs font-mono font-semibold text-purple-800">
                                    {{ $exp['start_date'] ?? '' }} – {{ $exp['end_date'] ?? 'Present' }}
                                </span>
                            </div>

                            @if(!empty($exp['bullets']))
                                <ul class="mt-2 space-y-1.5 text-xs text-slate-700 list-disc list-inside font-medium">
                                    @foreach(array_filter(explode("\n", $exp['bullets'])) as $bullet)
                                        <li>{{ trim($bullet) }}</li>
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
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-purple-900 border-b border-purple-100 pb-1 mb-3">
                    Education
                </h2>
                <div class="space-y-3">
                    @foreach($resume->education as $edu)
                        <div class="flex justify-between items-baseline text-xs">
                            <div>
                                <span class="font-bold text-slate-900">{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</span>
                                <span class="text-slate-600"> — {{ $edu['institution'] ?? '' }}</span>
                            </div>
                            <span class="font-mono text-slate-500 font-semibold">{{ $edu['year'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Technical & Professional Skills -->
        @if(!empty($resume->skills))
            <div>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-purple-900 border-b border-purple-100 pb-1 mb-3">
                    Skills & Competencies
                </h2>
                <div class="flex flex-wrap gap-2 text-xs">
                    @foreach($resume->skills as $category => $skillItems)
                        @php
                            $items = is_array($skillItems) ? implode(', ', $skillItems) : $skillItems;
                        @endphp
                        <div class="bg-purple-50 text-purple-900 border border-purple-200 px-3 py-1.5 rounded-lg font-semibold">
                            @if(is_string($category) && !is_numeric($category))
                                <strong>{{ ucfirst($category) }}:</strong>
                            @endif
                            {{ $items }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Projects -->
        @if(!empty($resume->projects))
            <div>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-purple-900 border-b border-purple-100 pb-1 mb-3">
                    Key Projects
                </h2>
                <div class="space-y-4">
                    @foreach($resume->projects as $proj)
                        <div>
                            <div class="flex justify-between items-baseline text-xs">
                                <span class="font-bold text-slate-900">{{ $proj['title'] ?? '' }}</span>
                                @if(!empty($proj['link'])) <span class="text-purple-600 font-mono text-[11px]">{{ $proj['link'] }}</span> @endif
                            </div>
                            @if(!empty($proj['role'])) <p class="text-[11px] text-purple-700 font-medium">{{ $proj['role'] }}</p> @endif
                            @if(!empty($proj['description'])) <p class="text-xs text-slate-600 mt-1">{{ $proj['description'] }}</p> @endif
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

        <!-- Certifications -->
        @if(!empty($resume->certifications))
            <div>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-purple-900 border-b border-purple-100 pb-1 mb-2">
                    Certifications & Licenses
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach($resume->certifications as $cert)
                        <div class="p-2.5 rounded-lg bg-purple-50/50 border border-purple-100">
                            <span class="font-bold text-slate-900">{{ $cert['name'] ?? '' }}</span>
                            @if(!empty($cert['issuer'])) <span class="text-slate-600 block text-[11px]">{{ $cert['issuer'] }}</span> @endif
                            @if(!empty($cert['year'])) <span class="text-purple-800 text-[10px] font-mono">{{ $cert['year'] }}</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Custom Additional Sections -->
        @if(!empty($resume->custom_sections))
            @foreach($resume->custom_sections as $cSec)
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-purple-900 border-b border-purple-100 pb-1 mb-2">
                        {{ strtoupper($cSec['title'] ?? 'ADDITIONAL SECTION') }}
                    </h2>
                    @if(!empty($cSec['content']))
                        <p class="text-xs text-slate-700 leading-relaxed mb-2">{{ $cSec['content'] }}</p>
                    @endif
                    @if(!empty($cSec['items']))
                        <ul class="space-y-1 text-xs text-slate-700 list-disc list-inside">
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
