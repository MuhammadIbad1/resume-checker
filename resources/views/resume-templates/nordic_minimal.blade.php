<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $resume->personal_info['full_name'] ?? 'Resume' }} — Nordic Minimal Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="bg-neutral-100 font-sans p-4 sm:p-8 text-neutral-800">

    <!-- Top Action Bar -->
    @include('resume-templates.partials.top_action_bar', ['templateName' => 'Nordic Minimal', 'atsReport' => $atsReport ?? null])

    <!-- Resume Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 border border-neutral-300 shadow-xl rounded-sm space-y-7">
        
        <!-- Header -->
        <div class="border-b border-neutral-900 pb-5">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-neutral-900">
                {{ $resume->personal_info['full_name'] ?? 'YOUR NAME' }}
            </h1>
            <p class="text-xs font-semibold tracking-widest uppercase text-neutral-500 mt-1">
                {{ $resume->personal_info['job_title'] ?? 'Professional Title' }}
            </p>
            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-neutral-600 font-medium">
                @if(!empty($resume->personal_info['email'])) <span>{{ $resume->personal_info['email'] }}</span> @endif
                @if(!empty($resume->personal_info['phone'])) <span>/ {{ $resume->personal_info['phone'] }}</span> @endif
                @if(!empty($resume->personal_info['location'])) <span>/ {{ $resume->personal_info['location'] }}</span> @endif
                @if(!empty($resume->personal_info['linkedin'])) <span>/ {{ $resume->personal_info['linkedin'] }}</span> @endif
            </div>
        </div>

        <!-- 01 / Summary -->
        @if(!empty($resume->personal_info['summary']))
            <div>
                <h2 class="text-xs font-black tracking-widest uppercase text-neutral-900 mb-2">
                    01 // PROFILE
                </h2>
                <p class="text-xs text-neutral-700 leading-relaxed font-normal">
                    {{ $resume->personal_info['summary'] }}
                </p>
            </div>
        @endif

        <!-- 02 / Experience -->
        @if(!empty($resume->work_experience))
            <div>
                <h2 class="text-xs font-black tracking-widest uppercase text-neutral-900 mb-4">
                    02 // WORK EXPERIENCE
                </h2>
                <div class="space-y-5">
                    @foreach($resume->work_experience as $exp)
                        <div>
                            <div class="flex justify-between items-baseline">
                                <h3 class="text-xs font-bold text-neutral-900">
                                    {{ $exp['role'] ?? '' }} — <span class="text-neutral-600 font-normal">{{ $exp['company'] ?? '' }}</span>
                                </h3>
                                <span class="text-[11px] font-mono text-neutral-500">
                                    {{ $exp['start_date'] ?? '' }} – {{ $exp['end_date'] ?? 'Present' }}
                                </span>
                            </div>

                            @if(!empty($exp['bullets']))
                                <ul class="mt-2 space-y-1 text-xs text-neutral-700 list-disc list-inside">
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

        <!-- 03 / Education -->
        @if(!empty($resume->education))
            <div>
                <h2 class="text-xs font-black tracking-widest uppercase text-neutral-900 mb-3">
                    03 // EDUCATION
                </h2>
                <div class="space-y-2">
                    @foreach($resume->education as $edu)
                        <div class="flex justify-between items-baseline text-xs">
                            <div>
                                <span class="font-bold text-neutral-900">{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</span>
                                <span class="text-neutral-500">, {{ $edu['institution'] ?? '' }}</span>
                            </div>
                            <span class="font-mono text-neutral-500 text-[11px]">{{ $edu['year'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 04 / Skills -->
        @if(!empty($resume->skills))
            <div>
                <h2 class="text-xs font-black tracking-widest uppercase text-neutral-900 mb-2">
                    04 // SKILLS
                </h2>
                <div class="space-y-1 text-xs text-neutral-700">
                    @foreach($resume->skills as $cat => $items)
                        <div>
                            @if(is_string($cat) && !is_numeric($cat))
                                <span class="font-bold text-neutral-900">{{ $cat }}:</span>
                            @endif
                            <span>{{ is_array($items) ? implode(', ', $items) : $items }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 05 / Projects -->
        @if(!empty($resume->projects))
            <div>
                <h2 class="text-xs font-black tracking-widest uppercase text-neutral-900 mb-2">
                    05 // PROJECTS
                </h2>
                <div class="space-y-3">
                    @foreach($resume->projects as $proj)
                        <div class="text-xs">
                            <div class="flex justify-between font-bold text-neutral-900">
                                <span>{{ $proj['title'] ?? '' }}</span>
                                @if(!empty($proj['role'])) <span class="font-normal text-neutral-500">{{ $proj['role'] }}</span> @endif
                            </div>
                            @if(!empty($proj['description'])) <p class="text-neutral-600 mt-0.5">{{ $proj['description'] }}</p> @endif
                            @if(!empty($proj['bullets']))
                                <ul class="mt-1 space-y-0.5 list-disc list-inside text-neutral-700">
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

        <!-- 06 / Certifications -->
        @if(!empty($resume->certifications))
            <div>
                <h2 class="text-xs font-black tracking-widest uppercase text-neutral-900 mb-2">
                    06 // CERTIFICATIONS
                </h2>
                <div class="space-y-1 text-xs text-neutral-700">
                    @foreach($resume->certifications as $cert)
                        <div>
                            <span class="font-bold text-neutral-900">{{ $cert['name'] ?? '' }}</span>
                            @if(!empty($cert['issuer'])) <span class="text-neutral-500"> — {{ $cert['issuer'] }}</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 07 / Custom Sections -->
        @if(!empty($resume->custom_sections))
            @php $secNum = 7; @endphp
            @foreach($resume->custom_sections as $cSec)
                <div>
                    <h2 class="text-xs font-black tracking-widest uppercase text-neutral-900 mb-2">
                        0{{ $secNum++ }} // {{ strtoupper($cSec['title'] ?? 'ADDITIONAL') }}
                    </h2>
                    @if(!empty($cSec['content']))
                        <p class="text-xs text-neutral-700 leading-relaxed mb-1">{{ $cSec['content'] }}</p>
                    @endif
                    @if(!empty($cSec['items']))
                        <ul class="space-y-0.5 text-xs text-neutral-700 list-disc list-inside">
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

