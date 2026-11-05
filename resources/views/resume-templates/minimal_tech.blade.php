<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $resume->personal_info['full_name'] ?? 'Resume' }} — Minimal Tech Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-mono p-4 sm:p-8 text-slate-900">

    <!-- Top Action Bar (Includes Live ATS Friendliness Audit) -->
    @include('resume-templates.partials.top_action_bar', ['templateName' => 'Minimal Tech', 'atsReport' => $atsReport ?? null])

    <!-- Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 border border-slate-300 shadow-xl rounded-sm space-y-7">
        
        <!-- Header -->
        <div class="border-b-2 border-indigo-600 pb-4 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">
                    {{ $resume->personal_info['full_name'] ?? 'YOUR NAME' }}
                </h1>
                <p class="text-xs font-bold text-indigo-600 mt-1 uppercase">
                    // {{ $resume->personal_info['job_title'] ?? 'Full Stack Software Engineer' }}
                </p>
            </div>
            <div class="text-right text-[11px] text-slate-600 space-y-0.5 font-sans">
                @if(!empty($resume->personal_info['email'])) <div>{{ $resume->personal_info['email'] }}</div> @endif
                @if(!empty($resume->personal_info['phone'])) <div>{{ $resume->personal_info['phone'] }}</div> @endif
                @if(!empty($resume->personal_info['github'])) <div>github: {{ $resume->personal_info['github'] }}</div> @endif
                @if(!empty($resume->personal_info['linkedin'])) <div>linkedin: {{ $resume->personal_info['linkedin'] }}</div> @endif
            </div>
        </div>

        <!-- Summary -->
        @if(!empty($resume->personal_info['summary']))
            <div>
                <h2 class="text-xs font-bold text-indigo-700 uppercase tracking-widest border-b border-slate-200 pb-1 mb-2">
                    // Summary
                </h2>
                <p class="text-xs text-slate-800 leading-relaxed font-sans">
                    {{ $resume->personal_info['summary'] }}
                </p>
            </div>
        @endif

        <!-- Technical Experience -->
        @if(!empty($resume->work_experience))
            <div>
                <h2 class="text-xs font-bold text-indigo-700 uppercase tracking-widest border-b border-slate-200 pb-1 mb-4">
                    // Engineering Experience
                </h2>
                <div class="space-y-5">
                    @foreach($resume->work_experience as $exp)
                        <div>
                            <div class="flex justify-between items-baseline text-xs">
                                <div>
                                    <strong class="text-slate-900 font-bold text-sm">{{ $exp['role'] ?? '' }}</strong>
                                    <span class="text-slate-600 font-sans"> @ {{ $exp['company'] ?? '' }}</span>
                                </div>
                                <span class="text-slate-500 font-semibold">
                                    [{{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? 'Present' }}]
                                </span>
                            </div>

                            @if(!empty($exp['bullets']))
                                <ul class="mt-2 space-y-1.5 text-xs text-slate-800 font-sans list-disc list-inside">
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
                <h2 class="text-xs font-bold text-indigo-700 uppercase tracking-widest border-b border-slate-200 pb-1 mb-3">
                    // Education
                </h2>
                <div class="space-y-2 text-xs font-sans">
                    @foreach($resume->education as $edu)
                        <div class="flex justify-between items-baseline">
                            <span class="font-bold text-slate-900">{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}, {{ $edu['institution'] ?? '' }}</span>
                            <span class="font-mono text-slate-500 font-semibold">{{ $edu['year'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Tech Stack Skills -->
        @if(!empty($resume->skills))
            <div>
                <h2 class="text-xs font-bold text-indigo-700 uppercase tracking-widest border-b border-slate-200 pb-1 mb-3">
                    // Tech Stack & Skills
                </h2>
                <div class="flex flex-wrap gap-2 text-xs font-mono">
                    @foreach($resume->skills as $cat => $list)
                        @php
                            $items = is_array($list) ? implode(', ', $list) : $list;
                        @endphp
                        <span class="bg-indigo-50 border border-indigo-200 text-indigo-900 px-2.5 py-1 rounded font-bold">
                            {{ $items }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Key Projects -->
        @if(!empty($resume->projects))
            <div>
                <h2 class="text-xs font-bold text-indigo-700 uppercase tracking-widest border-b border-slate-200 pb-1 mb-3">
                    // Key Projects
                </h2>
                <div class="space-y-3 font-mono">
                    @foreach($resume->projects as $proj)
                        <div class="text-xs">
                            <div class="flex justify-between font-bold text-slate-900">
                                <span>{{ $proj['title'] ?? '' }}</span>
                                @if(!empty($proj['link'])) <span class="font-normal text-indigo-600">{{ $proj['link'] }}</span> @endif
                            </div>
                            @if(!empty($proj['role'])) <p class="text-[11px] text-indigo-800">Role: {{ $proj['role'] }}</p> @endif
                            @if(!empty($proj['description'])) <p class="text-slate-600 mt-0.5 font-sans">{{ $proj['description'] }}</p> @endif
                            @if(!empty($proj['bullets']))
                                <ul class="mt-1 space-y-0.5 list-disc list-inside text-slate-700 font-sans">
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
                <h2 class="text-xs font-bold text-indigo-700 uppercase tracking-widest border-b border-slate-200 pb-1 mb-2">
                    // Certifications
                </h2>
                <div class="space-y-1 text-xs font-mono">
                    @foreach($resume->certifications as $cert)
                        <div>
                            <span class="font-bold text-slate-900">{{ $cert['name'] ?? '' }}</span>
                            @if(!empty($cert['issuer'])) <span class="text-slate-500 font-sans"> — {{ $cert['issuer'] }}</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Custom Additional Sections -->
        @if(!empty($resume->custom_sections))
            @foreach($resume->custom_sections as $cSec)
                <div>
                    <h2 class="text-xs font-bold text-indigo-700 uppercase tracking-widest border-b border-slate-200 pb-1 mb-2">
                        // {{ strtoupper($cSec['title'] ?? 'ADDITIONAL') }}
                    </h2>
                    @if(!empty($cSec['content']))
                        <p class="text-xs text-slate-700 mb-1 font-sans">{{ $cSec['content'] }}</p>
                    @endif
                    @if(!empty($cSec['items']))
                        <ul class="space-y-0.5 text-xs text-slate-700 list-disc list-inside font-sans">
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
