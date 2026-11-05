<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
    
    <!-- Top Header & Current Subscription Banner -->
    <div class="border-b border-purple-100 dark:border-slate-800 pb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 transition-colors">
        <div>
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800 mb-3 shadow-sm">
                💳 Dual Payment Engine
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Subscription & Plan Upgrades</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                Choose between Stripe International Checkout or Local Manual Transfers (JazzCash, Easypaisa, Bank Transfer).
            </p>
        </div>

        <!-- Active Subscription Badge -->
        <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-2xl p-5 flex items-center space-x-4 shadow-md shadow-purple-500/5 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 border border-purple-200 dark:border-purple-800 flex items-center justify-center text-purple-700 dark:text-purple-300 font-bold text-xl">
                ★
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-bold">Active Plan</p>
                <h4 class="text-lg font-black text-slate-900 dark:text-white">{{ $currentPlan->name ?? 'Free Starter' }}</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    @if($activeSub)
                        Valid until {{ $activeSub->ends_at ? $activeSub->ends_at->format('M d, Y') : 'Lifetime' }}
                    @else
                        Standard Free Tier (3 scans/mo)
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Monthly / Yearly Toggle -->
    <div class="flex justify-center">
        <div class="bg-white dark:bg-slate-900 p-1.5 rounded-2xl border border-purple-100 dark:border-slate-800 shadow-sm flex items-center space-x-2 transition-colors">
            <button 
                wire:click="$set('billingCycle', 'monthly')" 
                class="px-5 py-2.5 rounded-xl text-sm font-bold transition {{ $billingCycle === 'monthly' ? 'bg-purple-600 text-white shadow-md shadow-purple-500/25' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}"
            >
                Monthly Billing
            </button>
            <button 
                wire:click="$set('billingCycle', 'yearly')" 
                class="px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center space-x-2 {{ $billingCycle === 'yearly' ? 'bg-purple-600 text-white shadow-md shadow-purple-500/25' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}"
            >
                <span>Yearly Billing</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800 uppercase font-extrabold">
                    Save 30%
                </span>
            </button>
        </div>
    </div>

    <!-- Pricing Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($plans as $planItem)
            @php
                $isCurrent = $currentPlan && $currentPlan->id === $planItem->id;
                $usdPrice = $billingCycle === 'yearly' ? $planItem->price_yearly : $planItem->price_monthly;
                $pkrPrice = $billingCycle === 'yearly' ? $planItem->price_pkr_yearly : $planItem->price_pkr_monthly;
            @endphp
            <div class="bg-white dark:bg-slate-900 border {{ $planItem->is_popular ? 'border-purple-400 dark:border-purple-500 shadow-2xl shadow-purple-500/10 ring-2 ring-purple-500/20' : 'border-purple-100 dark:border-slate-800' }} rounded-3xl p-8 flex flex-col justify-between relative backdrop-blur-xl transition-colors">
                
                @if($planItem->is_popular)
                    <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-[11px] font-extrabold uppercase tracking-wider shadow-md">
                        Most Popular for Job Seekers
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $planItem->name }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 h-10 font-medium">{{ $planItem->description }}</p>
                    </div>

                    <!-- Price display (USD + PKR) -->
                    <div class="py-4 border-y border-purple-100 dark:border-slate-800">
                        <div class="flex items-baseline space-x-2">
                            <span class="text-4xl font-black text-slate-900 dark:text-white font-mono">${{ number_format($usdPrice, 0) }}</span>
                            <span class="text-slate-500 dark:text-slate-400 text-xs font-semibold">/ {{ $billingCycle === 'yearly' ? 'year' : 'month' }}</span>
                        </div>
                        @if($pkrPrice > 0)
                            <p class="text-xs text-purple-700 dark:text-purple-400 font-bold mt-1 font-mono">
                                Approx. Rs. {{ number_format($pkrPrice) }} PKR (Local Transfer)
                            </p>
                        @endif
                    </div>

                    <!-- Feature bullet list -->
                    <ul class="space-y-3 text-sm text-slate-700 dark:text-slate-300">
                        @foreach(($planItem->features ?? []) as $feat)
                            <li class="flex items-center space-x-3 text-xs font-semibold">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="pt-8">
                    @if($isCurrent)
                        <button disabled class="w-full py-3.5 px-4 rounded-2xl font-bold text-sm bg-purple-50 dark:bg-slate-800 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-slate-700 cursor-default">
                            ✓ Current Plan
                        </button>
                    @elseif($planItem->slug === 'free')
                        <button disabled class="w-full py-3.5 px-4 rounded-2xl font-bold text-sm bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-default">
                            Default Plan
                        </button>
                    @else
                        <button 
                            wire:click="selectPlan({{ $planItem->id }})" 
                            class="w-full py-3.5 px-4 rounded-2xl font-bold text-sm bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 transition shadow-lg shadow-purple-500/25"
                        >
                            Upgrade to {{ $planItem->name }}
                        </button>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

    <!-- Payment Checkout Modal -->
    @if($showPaymentModal && $selectedPlan)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">
            <div class="bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 rounded-3xl max-w-2xl w-full p-8 space-y-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
                <button wire:click="closeModal" class="absolute top-6 right-6 text-slate-400 hover:text-slate-900 dark:hover:text-white text-xl font-bold">
                    ✕
                </button>

                <div>
                    <span class="text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider">Checkout</span>
                    <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">
                        Subscribe to {{ $selectedPlan->name }} ({{ ucfirst($billingCycle) }})
                    </h3>
                </div>

                <!-- Payment Method Toggle Tabs -->
                <div class="grid grid-cols-2 gap-4">
                    <button 
                        wire:click="$set('paymentMethod', 'manual')" 
                        class="p-4 rounded-2xl border text-left transition flex items-center space-x-3 {{ $paymentMethod === 'manual' ? 'border-purple-600 bg-purple-50 dark:bg-purple-950/60 text-purple-900 dark:text-purple-200' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400' }}"
                    >
                        <span class="text-2xl">🇵🇰</span>
                        <div>
                            <p class="text-sm font-bold">Local Manual Transfer</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">JazzCash, Easypaisa, Bank IBFT</p>
                        </div>
                    </button>

                    <button 
                        wire:click="$set('paymentMethod', 'stripe')" 
                        class="p-4 rounded-2xl border text-left transition flex items-center space-x-3 {{ $paymentMethod === 'stripe' ? 'border-purple-600 bg-purple-50 dark:bg-purple-950/60 text-purple-900 dark:text-purple-200' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400' }}"
                    >
                        <span class="text-2xl">💳</span>
                        <div>
                            <p class="text-sm font-bold">Credit Card (Stripe)</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Instant International Checkout</p>
                        </div>
                    </button>
                </div>

                @if($paymentMethod === 'manual')
                    <!-- Local Manual Instructions Card -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Select Gateway Account:</label>
                            <select wire:model.live="manualMethod" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs rounded-xl px-3 py-2 font-semibold">
                                <option value="JazzCash">JazzCash Mobile Account</option>
                                <option value="Easypaisa">Easypaisa Mobile Account</option>
                                <option value="Bank Transfer">Meezan Bank Limited</option>
                            </select>
                        </div>

                        <div class="p-4 rounded-2xl bg-purple-50/70 dark:bg-slate-800/80 border border-purple-100 dark:border-slate-700 text-xs text-purple-950 dark:text-purple-200 space-y-2">
                            @if($manualMethod === 'JazzCash')
                                <p><strong>Title:</strong> {{ $jazzcash['account_title'] ?? 'TechDost Solutions' }}</p>
                                <p><strong>JazzCash Number:</strong> <span class="text-purple-700 dark:text-purple-400 font-mono font-extrabold">{{ $jazzcash['account_number'] ?? '0300-1234567' }}</span></p>
                                <p class="text-slate-600 dark:text-slate-300">{{ $jazzcash['instructions'] ?? '' }}</p>
                            @elseif($manualMethod === 'Easypaisa')
                                <p><strong>Title:</strong> {{ $easypaisa['account_title'] ?? 'TechDost Solutions' }}</p>
                                <p><strong>Easypaisa Number:</strong> <span class="text-purple-700 dark:text-purple-400 font-mono font-extrabold">{{ $easypaisa['account_number'] ?? '0312-9876543' }}</span></p>
                                <p class="text-slate-600 dark:text-slate-300">{{ $easypaisa['instructions'] ?? '' }}</p>
                            @else
                                <p><strong>Bank:</strong> {{ $bank['title'] ?? 'Meezan Bank Limited' }}</p>
                                <p><strong>Account Title:</strong> {{ $bank['account_title'] ?? 'TechDost Solutions' }}</p>
                                <p><strong>IBAN:</strong> <span class="text-purple-700 dark:text-purple-400 font-mono font-extrabold">{{ $bank['iban'] ?? 'PK36MEZN0001020104882194' }}</span></p>
                                <p class="text-slate-600 dark:text-slate-300">{{ $bank['instructions'] ?? '' }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Transaction ID / Reference Number</label>
                            <input wire:model="reference" type="text" placeholder="e.g. TRX-9821448 or IBFT Reference" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-purple-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Upload Receipt Screenshot (Image)</label>
                            <input type="file" wire:model="proofFile" accept="image/*" class="text-xs text-slate-600 dark:text-slate-400">
                            @error('proofFile') <span class="text-xs text-rose-600 dark:text-rose-400 block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @else
                    <div class="p-6 rounded-2xl bg-purple-50 dark:bg-slate-800 border border-purple-200 dark:border-slate-700 text-xs text-purple-950 dark:text-purple-200 space-y-2">
                        <p class="font-bold text-sm text-purple-900 dark:text-purple-300">Stripe Hosted Checkout Simulation</p>
                        <p class="text-slate-600 dark:text-slate-300">Clicking submit will redirect you to the Stripe card checkout gateway test session. Upon successful completion, your subscription will activate instantly.</p>
                    </div>
                @endif

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <button wire:click="closeModal" type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                        Cancel
                    </button>
                    <button 
                        wire:click="processCheckout" 
                        type="button" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 shadow-lg shadow-purple-500/25"
                    >
                        Submit Payment & Activate
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
