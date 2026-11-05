<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-slate-900 p-8 rounded-3xl border border-purple-100 dark:border-slate-800 shadow-2xl shadow-purple-500/5 transition-colors">
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center mx-auto shadow-md shadow-purple-500/20 mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Create Free Account</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Get 3 free ATS scans & keyword reports per month</p>
        </div>

        <form wire:submit="register" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Full Name</label>
                <input wire:model="name" type="text" required class="mt-1 block w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-purple-600 dark:focus:border-purple-400 focus:ring-2 focus:ring-purple-600/20 transition">
                @error('name') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Email Address</label>
                <input wire:model="email" type="email" required class="mt-1 block w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-purple-600 dark:focus:border-purple-400 focus:ring-2 focus:ring-purple-600/20 transition">
                @error('email') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
                <input wire:model="password" type="password" required class="mt-1 block w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-purple-600 dark:focus:border-purple-400 focus:ring-2 focus:ring-purple-600/20 transition">
                @error('password') <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Confirm Password</label>
                <input wire:model="password_confirmation" type="password" required class="mt-1 block w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-purple-600 dark:focus:border-purple-400 focus:ring-2 focus:ring-purple-600/20 transition">
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 transition shadow-lg shadow-purple-500/25">
                Create Account & Start Scanning
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 dark:text-slate-400">
            Already registered? <a href="{{ route('login') }}" class="text-purple-600 dark:text-purple-400 font-bold hover:underline">Sign in here</a>
        </p>
    </div>
</div>
