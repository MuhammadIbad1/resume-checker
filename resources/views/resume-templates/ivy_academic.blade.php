<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $resume->personal_info['full_name'] ?? 'Resume' }} — Ivy Academic Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="bg-stone-100 font-serif p-4 sm:p-8 text-stone-900">

    <!-- Top Action Bar -->
    @include('resume-templates.partials.top_action_bar', ['templateName' => 'Ivy Academic', 'atsReport' => $atsReport ?? null])

    <!-- Resume Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-12 sm:p-16 border border-stone-300 shadow-xl rounded-sm space-y-7">
        
        <!-- Header -->
        <div class="text-center border-b-2 border-stone-800 pb-5">
            <h1 class="text-3xl sm:text-4xl font-normal uppercase tracking-widest text-stone-900 font-serif">
                {{ $resume->personal_info['full_name'] ?? 'YOUR NAME' }}
            </h1>
            <p class="text-xs uppercase tracking-widest font-sans font-bold text-stone-600 mt-1.5">
                {{ $resume->personal_info['job_title'] ?? 'Professional Title' }}
            </p>
            <div class="mt-2.5 text-xs font-sans text-stone-600 space-x-3">
                @if(!empty($resume->personal_info['email'])) <span>{{ $resume->personal_info['email'] }}</span> @endif
                @if(!empty($resume->personal_info['phone'])) <span>• {{ $resume->personal_info['phone'] }}</span> @endif
                @if(!empty($resume->personal_info['location'])) <span>• {{ $resume->personal_info['location'] }}</span> @endif
                @if(!empty($resume->personal_info['linkedin'])) <span>• {{ $resume->personal_info['linkedin'] }}</span> @endif
            </div>
        </div>

        <!-- Summary -->
        @if(!empty($resume->personal_info['summary']))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-stone-900 border-b border-stone-300 pb-1 mb-2">
                    Executive Profile
                </h2>
                <p class="text-xs leading-relaxed text-stone-800 text-justify">
                    {{ $resume->personal_info['summary'] }}
                </p>
            </div>
        @endif

        <!-- Education (High Prominence for Ivy Academic) -->
        @if(!empty($resume->education))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-stone-900 border-b border-stone-300 pb-1 mb-3">
                    Education & Credentials
                </h2>
                <div class="space-y-3">
                    @foreach($resume->education as $edu)
                        <div class="flex justify-between items-baseline text-xs">
                            <div>
                                <span class="font-bold text-stone-900">{{ $edu['institution'] ?? '' }}</span>
                                <span class="text-stone-700 italic block">{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</span>
                            </div>
                            <span class="font-sans text-stone-600 font-semibold text-xs">{{ $edu['year'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Work Experience -->
        @if(!empty($resume->work_experience))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-stone-900 border-b border-stone-300 pb-1 mb-4">
                    Professional Experience
                </h2>
                <div class="space-y-5">
                    @foreach($resume->work_experience as $exp)
                        <div>
                            <div class="flex justify-between items-baseline">
                                <div>
                                    <h3 class="text-sm font-bold text-stone-900">{{ $exp['role'] ?? '' }}</h3>
                                    <span class="text-xs italic text-stone-700 block">{{ $exp['company'] ?? '' }}</span>
                                </div>
                                <span class="text-xs font-sans text-stone-600 font-medium">
                                    {{ $exp['start_date'] ?? '' }} – {{ $exp['end_date'] ?? 'Present' }}
                                </span>
                            </div>

                            @if(!empty($exp['bullets']))
                                <ul class="mt-2 space-y-1 text-xs text-stone-800 list-disc list-inside">
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
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-stone-900 border-b border-stone-300 pb-1 mb-3">
                    Key Engagements & Projects
                </h2>
                <div class="space-y-3">
                    @foreach($resume->projects as $proj)
                        <div class="text-xs">
                            <div class="flex justify-between font-bold text-stone-900">
                                <span>{{ $proj['title'] ?? '' }}</span>
                                @if(!empty($proj['link'])) <span class="font-sans font-normal text-stone-500">{{ $proj['link'] }}</span> @endif
                            </div>
                            @if(!empty($proj['role'])) <p class="text-[11px] text-stone-600 italic">{{ $proj['role'] }}</p> @endif
                            @if(!empty($proj['description'])) <p class="text-stone-700 mt-0.5">{{ $proj['description'] }}</p> @endif
                            @if(!empty($proj['bullets']))
                                <ul class="mt-1 space-y-0.5 list-disc list-inside text-stone-700">
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

        <!-- Skills -->
        @if(!empty($resume->skills))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-stone-900 border-b border-stone-300 pb-1 mb-2">
                    Areas of Expertise & Skills
                </h2>
                <div class="space-y-1.5 text-xs text-stone-800 font-sans">
                    @foreach($resume->skills as $cat => $items)
                        <div>
                            @if(is_string($cat) && !is_numeric($cat))
                                <span class="font-bold text-stone-900">{{ $cat }}:</span>
                            @endif
                            <span>{{ is_array($items) ? implode(', ', $items) : $items }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Certifications -->
        @if(!empty($resume->certifications))
            <div>
                <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-stone-900 border-b border-stone-300 pb-1 mb-2">
                    Honors & Certifications
                </h2>
                <div class="space-y-1 text-xs text-stone-800">
                    @foreach($resume->certifications as $cert)
                        <div>
                            <span class="font-bold">{{ $cert['name'] ?? '' }}</span>
                            @if(!empty($cert['issuer'])) <span> — {{ $cert['issuer'] }}</span> @endif
                            @if(!empty($cert['year'])) <span class="text-stone-500 font-sans">({{ $cert['year'] }})</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Custom Additional Sections -->
        @if(!empty($resume->custom_sections))
            @foreach($resume->custom_sections as $cSec)
                <div>
                    <h2 class="text-xs font-bold font-sans uppercase tracking-widest text-stone-900 border-b border-stone-300 pb-1 mb-2">
                        {{ strtoupper($cSec['title'] ?? 'ADDITIONAL CREDENTIALS') }}
                    </h2>
                    @if(!empty($cSec['content']))
                        <p class="text-xs text-stone-800 leading-relaxed mb-1">{{ $cSec['content'] }}</p>
                    @endif
                    @if(!empty($cSec['items']))
                        <ul class="space-y-1 text-xs text-stone-800 list-disc list-inside">
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

