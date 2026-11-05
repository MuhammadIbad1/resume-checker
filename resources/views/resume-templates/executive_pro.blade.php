<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $resume->personal_info['full_name'] ?? 'Resume' }} — Executive Pro Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans p-4 sm:p-8 text-slate-900">

    <!-- Top Action Bar (Includes Live ATS Friendliness Audit) -->
    @include('resume-templates.partials.top_action_bar', ['templateName' => 'Executive Pro', 'atsReport' => $atsReport ?? null])

    <!-- Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 border border-slate-300 shadow-xl rounded-sm space-y-7">
        
        <!-- Executive Header -->
        <div class="bg-slate-900 text-white p-8 rounded-xl flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-white">
                    {{ $resume->personal_info['full_name'] ?? 'YOUR NAME' }}
                </h1>
                <p class="text-xs font-bold uppercase tracking-wider text-purple-300 mt-1">
                    {{ $resume->personal_info['job_title'] ?? 'Executive Leader' }}
                </p>
            </div>

            <div class="text-xs text-slate-300 space-y-1 font-medium sm:text-right">
                @if(!empty($resume->personal_info['email'])) <div>{{ $resume->personal_info['email'] }}</div> @endif
                @if(!empty($resume->personal_info['phone'])) <div>{{ $resume->personal_info['phone'] }}</div> @endif
                @if(!empty($resume->personal_info['location'])) <div>{{ $resume->personal_info['location'] }}</div> @endif
                @if(!empty($resume->personal_info['linkedin'])) <div>{{ $resume->personal_info['linkedin'] }}</div> @endif
            </div>
        </div>

        <!-- Summary -->
        @if(!empty($resume->personal_info['summary']))
            <div class="bg-purple-50/60 border-l-4 border-purple-800 p-4 rounded-r-xl">
                <h2 class="text-xs font-extrabold uppercase tracking-widest text-purple-900 mb-1">
                    Executive Profile
                </h2>
                <p class="text-xs text-slate-800 leading-relaxed font-medium">
                    {{ $resume->personal_info['summary'] }}
                </p>
            </div>
        @endif

        <!-- Leadership & Experience -->
        @if(!empty($resume->work_experience))
            <div>
                <h2 class="text-xs font-extrabold uppercase tracking-widest text-purple-900 border-b border-purple-100 pb-1 mb-4">
                    Leadership & Career Experience
                </h2>
                <div class="space-y-6">
                    @foreach($resume->work_experience as $exp)
                        <div>
                            <div class="flex justify-between items-baseline">
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">{{ $exp['role'] ?? '' }}</h3>
                                    <p class="text-xs font-bold text-purple-900">{{ $exp['company'] ?? '' }}</p>
                                </div>
                                <span class="text-xs font-mono font-bold text-slate-500">
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
                <h2 class="text-xs font-extrabold uppercase tracking-widest text-purple-900 border-b border-purple-100 pb-1 mb-3">
                    Education & Credentials
                </h2>
                <div class="space-y-2 text-xs">
                    @foreach($resume->education as $edu)
                        <div class="flex justify-between items-baseline">
                            <span class="font-bold text-slate-900">{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }} — {{ $edu['institution'] ?? '' }}</span>
                            <span class="font-mono text-slate-500 font-semibold">{{ $edu['year'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Core Competencies -->
        @if(!empty($resume->skills))
            <div>
                <h2 class="text-xs font-extrabold uppercase tracking-widest text-purple-900 border-b border-purple-100 pb-1 mb-3">
                    Core Competencies & Expertise
                </h2>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    @foreach($resume->skills as $cat => $list)
                        @php
                            $items = is_array($list) ? implode(', ', $list) : $list;
                        @endphp
                        <div class="p-2 rounded bg-slate-50 border border-slate-200">
                            <strong class="text-purple-900 font-bold block">{{ is_string($cat) && !is_numeric($cat) ? ucfirst($cat) : 'Skills' }}</strong>
                            <span class="text-slate-700">{{ $items }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Projects -->
        @if(!empty($resume->projects))
            <div>
                <h2 class="text-xs font-black uppercase tracking-widest text-purple-900 border-b border-purple-200 pb-1 mb-2">
                    Key Initiatives & Projects
                </h2>
                <div class="space-y-3">
                    @foreach($resume->projects as $proj)
                        <div class="text-xs">
                            <div class="flex justify-between font-bold text-slate-900">
                                <span>{{ $proj['title'] ?? '' }}</span>
                                @if(!empty($proj['role'])) <span class="text-purple-700 font-semibold">{{ $proj['role'] }}</span> @endif
                            </div>
                            @if(!empty($proj['description'])) <p class="text-slate-600 mt-0.5">{{ $proj['description'] }}</p> @endif
                            @if(!empty($proj['bullets']))
                                <ul class="mt-1 space-y-0.5 list-disc list-inside text-slate-700">
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
                <h2 class="text-xs font-black uppercase tracking-widest text-purple-900 border-b border-purple-200 pb-1 mb-2">
                    Executive Credentials & Certifications
                </h2>
                <div class="space-y-1 text-xs text-slate-700">
                    @foreach($resume->certifications as $cert)
                        <div>
                            <span class="font-bold text-slate-900">{{ $cert['name'] ?? '' }}</span>
                            @if(!empty($cert['issuer'])) <span class="text-slate-500"> — {{ $cert['issuer'] }}</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Custom Additional Sections -->
        @if(!empty($resume->custom_sections))
            @foreach($resume->custom_sections as $cSec)
                <div>
                    <h2 class="text-xs font-black uppercase tracking-widest text-purple-900 border-b border-purple-200 pb-1 mb-2">
                        {{ strtoupper($cSec['title'] ?? 'ADDITIONAL') }}
                    </h2>
                    @if(!empty($cSec['content']))
                        <p class="text-xs text-slate-700 mb-1 leading-relaxed">{{ $cSec['content'] }}</p>
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
