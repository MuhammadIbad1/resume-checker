<!-- Top Action Bar (Hide during print) -->
<div class="no-print max-w-4xl mx-auto mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-purple-100 shadow-md font-sans space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                Template: {{ $templateName ?? 'ATS Standard' }}
            </span>
            <span class="text-xs text-slate-500 font-medium">{{ $resume->title }}</span>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- ATS Friendliness Score Pill Button -->
            @if(isset($atsReport))
                <button 
                    onclick="document.getElementById('ats-audit-modal').classList.toggle('hidden')"
                    class="px-3.5 py-2 text-xs font-extrabold rounded-xl border flex items-center space-x-1.5 shadow-sm transition hover:scale-105 {{ $atsReport['badgeColor'] }}"
                    title="Click to view full ATS Friendliness Audit"
                >
                    <span>{{ $atsReport['icon'] }}</span>
                    <span>ATS Friendly: <strong>{{ $atsReport['score'] }}%</strong></span>
                    <span class="text-[10px] underline ml-1 font-normal">(View Audit)</span>
                </button>
            @endif

            <a href="{{ !empty($resume->id) ? route('builder.edit', ['userResume' => $resume->id]) : route('builder.create') }}" class="px-3.5 py-2 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition">
                ✏️ Edit CV
            </a>

            <a href="{{ route('resumes.index') }}" class="px-3.5 py-2 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition">
                ← Resumes
            </a>

            <button onclick="window.print()" class="px-4 py-2 text-xs font-extrabold bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl shadow-md hover:opacity-95 transition flex items-center space-x-1.5">
                <span>🖨️ Download / Print PDF</span>
            </button>
        </div>
    </div>

    <!-- Inline ATS Friendliness Banner -->
    @if(isset($atsReport))
        <div class="pt-3 border-t border-purple-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-600 gap-2">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-slate-800">ATS Rating:</span>
                <span class="font-semibold text-purple-700">{{ $atsReport['grade'] }} ({{ $atsReport['score'] }}/100)</span>
                <span class="text-slate-300">•</span>
                <span>{{ $atsReport['totalSections'] }} Sections Parsed</span>
            </div>
            @if(!empty($atsReport['tips']))
                <div class="text-purple-700 font-medium truncate max-w-md">
                    💡 <strong>Tip:</strong> {{ $atsReport['tips'][0] }}
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Detailed ATS Friendliness Audit Modal -->
@if(isset($atsReport))
<div id="ats-audit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm no-print">
    <div class="bg-white border border-purple-200 rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('ats-audit-modal').classList.add('hidden')" class="absolute top-6 right-6 text-slate-400 hover:text-slate-800 text-xl font-bold">
            ✕
        </button>

        <!-- Header -->
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex flex-col items-center justify-center font-mono font-black shadow-lg shadow-purple-500/25 shrink-0">
                <span class="text-2xl leading-none">{{ $atsReport['score'] }}</span>
                <span class="text-[9px] uppercase font-bold text-purple-200">/ 100</span>
            </div>
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold border {{ $atsReport['badgeColor'] }}">
                    {{ $atsReport['icon'] }} {{ $atsReport['grade'] }}
                </span>
                <h3 class="text-xl font-extrabold text-slate-900 mt-1">
                    ATS Friendliness & Compatibility Scorecard
                </h3>
                <p class="text-xs text-slate-500">Evaluated against enterprise Applicant Tracking System parser standards.</p>
            </div>
        </div>

        <!-- Progress Bars Checklist -->
        <div class="space-y-3.5 pt-2">
            @foreach($atsReport['checks'] as $check)
                <div class="p-3.5 rounded-xl border {{ $check['passed'] ? 'bg-purple-50/50 border-purple-100' : 'bg-amber-50/50 border-amber-200' }}">
                    <div class="flex items-center justify-between text-xs font-bold mb-1">
                        <span class="flex items-center space-x-2">
                            <span>{{ $check['passed'] ? '✓' : '⚠' }}</span>
                            <span class="text-slate-900">{{ $check['name'] }}</span>
                        </span>
                        <span class="font-mono text-purple-800">{{ $check['score'] }} / {{ $check['max'] }} pts</span>
                    </div>
                    <p class="text-[11px] text-slate-600">{{ $check['details'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Recommendations to reach 100% -->
        @if(!empty($atsReport['tips']))
            <div class="p-4 rounded-2xl bg-purple-50 border border-purple-200 text-xs text-purple-950 space-y-2">
                <h4 class="font-bold text-purple-900 flex items-center space-x-1.5">
                    <span>💡 Recommendations to boost your score:</span>
                </h4>
                <ul class="space-y-1 list-disc list-inside text-slate-700">
                    @foreach($atsReport['tips'] as $tip)
                        <li>{{ $tip }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
            <button onclick="document.getElementById('ats-audit-modal').classList.add('hidden')" type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200">
                Close
            </button>
            <a href="{{ !empty($resume->id) ? route('builder.edit', ['userResume' => $resume->id]) : route('builder.create') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-purple-600 text-white hover:bg-purple-700 shadow-md">
                Edit Resume to Fix
            </a>
        </div>
    </div>
</div>
@endif
