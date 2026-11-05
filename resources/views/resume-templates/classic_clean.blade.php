<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $resume->personal_info['full_name'] ?? 'Resume' }} — Classic Corporate Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-serif p-4 sm:p-8 text-slate-900">

    <!-- Top Action Bar (Includes Live ATS Friendliness Audit) -->
    @include('resume-templates.partials.top_action_bar', ['templateName' => 'Classic Corporate', 'atsReport' => $atsReport ?? null])

    <!-- Resume Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 border border-slate-300 shadow-xl rounded-sm space-y-7">
        
        <!-- Header -->
        <div class="text-center border-b-2 border-slate-900 pb-4">
            <h1 class="text-3xl font-black uppercase tracking-widest text-slate-900">
                {{ $resume->personal_info['full_name'] ?? 'YOUR NAME' }}
            </h1>
            <p class="text-xs uppercase tracking-wider font-sans font-bold text-slate-600 mt-1">
                {{ $resume->personal_info['job_title'] ?? 'Professional Title' }}
            </p>
            <div class="mt-2 text-xs font-sans text-slate-600 space-x-3">
                @if(!empty($resume->personal_info['email'])) <span>{{ $resume->personal_info['email'] }}</span> • @endif
                @if(!empty($resume->personal_info['phone'])) <span>{{ $resume->personal_info['phone'] }}</span> • @endif
                @if(!empty($resume->personal_info['location'])) <span>{{ $resume->personal_info['location'] }}</span> • @endif
                @if(!empty($resume->personal_info['linkedin'])) <span>{{ $resume->personal_info['linkedin'] }}</span> @endif
            </div>
        </div>

        <!-- Professional Summary -->
        @if(!empty($resume->personal_info['summary']))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-slate-900 border-b border-slate-300 pb-1 mb-2">
                    Executive Summary
                </h2>
                <p class="text-xs leading-relaxed text-slate-800">
                    {{ $resume->personal_info['summary'] }}
                </p>
            </div>
        @endif

        <!-- Experience -->
        @if(!empty($resume->work_experience))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-slate-900 border-b border-slate-300 pb-1 mb-4">
                    Professional Experience
                </h2>
                <div class="space-y-5">
                    @foreach($resume->work_experience as $exp)
                        <div>
                            <div class="flex justify-between items-baseline text-xs font-sans">
                                <div>
                                    <strong class="text-slate-900 font-bold text-sm">{{ $exp['role'] ?? '' }}</strong>
                                    <span class="text-slate-700 italic"> — {{ $exp['company'] ?? '' }}</span>
                                </div>
                                <span class="font-mono text-slate-600 font-semibold">
                                    {{ $exp['start_date'] ?? '' }} – {{ $exp['end_date'] ?? 'Present' }}
                                </span>
                            </div>

                            @if(!empty($exp['bullets']))
                                <ul class="mt-2 space-y-1 text-xs text-slate-800 list-disc list-inside">
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
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-slate-900 border-b border-slate-300 pb-1 mb-3">
                    Education & Credentials
                </h2>
                <div class="space-y-2 text-xs font-sans">
                    @foreach($resume->education as $edu)
                        <div class="flex justify-between items-baseline">
                            <span class="font-bold text-slate-900">{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}, {{ $edu['institution'] ?? '' }}</span>
                            <span class="font-mono text-slate-600 font-semibold">{{ $edu['year'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Skills -->
        @if(!empty($resume->skills))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-slate-900 border-b border-slate-300 pb-1 mb-2">
                    Core Technical Competencies
                </h2>
                <p class="text-xs font-sans text-slate-800 leading-relaxed">
                    @foreach($resume->skills as $cat => $list)
                        <strong>{{ is_string($cat) && !is_numeric($cat) ? ucfirst($cat) . ': ' : '' }}</strong>
                        {{ is_array($list) ? implode(', ', $list) : $list }} |
                    @endforeach
                </p>
            </div>
        @endif

        <!-- Projects -->
        @if(!empty($resume->projects))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-slate-900 border-b border-slate-300 pb-1 mb-2">
                    Key Projects
                </h2>
                <div class="space-y-3">
                    @foreach($resume->projects as $proj)
                        <div class="text-xs">
                            <div class="flex justify-between font-bold text-slate-900">
                                <span>{{ $proj['title'] ?? '' }}</span>
                                @if(!empty($proj['link'])) <span class="font-sans font-normal text-slate-500">{{ $proj['link'] }}</span> @endif
                            </div>
                            @if(!empty($proj['role'])) <p class="text-[11px] text-slate-600 italic">{{ $proj['role'] }}</p> @endif
                            @if(!empty($proj['description'])) <p class="text-slate-800 mt-0.5">{{ $proj['description'] }}</p> @endif
                            @if(!empty($proj['bullets']))
                                <ul class="mt-1 space-y-0.5 list-disc list-inside text-slate-800">
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
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-slate-900 border-b border-slate-300 pb-1 mb-2">
                    Certifications & Affiliations
                </h2>
                <div class="space-y-1 text-xs text-slate-800">
                    @foreach($resume->certifications as $cert)
                        <div>
                            <span class="font-bold">{{ $cert['name'] ?? '' }}</span>
                            @if(!empty($cert['issuer'])) <span> — {{ $cert['issuer'] }}</span> @endif
                            @if(!empty($cert['year'])) <span class="text-slate-500 font-sans">({{ $cert['year'] }})</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Custom Additional Sections -->
        @if(!empty($resume->custom_sections))
            @foreach($resume->custom_sections as $cSec)
                <div>
                    <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-slate-900 border-b border-slate-300 pb-1 mb-2">
                        {{ strtoupper($cSec['title'] ?? 'ADDITIONAL CREDENTIALS') }}
                    </h2>
                    @if(!empty($cSec['content']))
                        <p class="text-xs text-slate-800 leading-relaxed mb-1">{{ $cSec['content'] }}</p>
                    @endif
                    @if(!empty($cSec['items']))
                        <ul class="space-y-1 text-xs text-slate-800 list-disc list-inside">
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
