<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-slate-900 p-8 rounded-3xl border border-purple-100 dark:border-slate-800 shadow-2xl shadow-purple-500/5 transition-colors">
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center mx-auto shadow-md shadow-purple-500/20 mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Welcome Back</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Sign in to check your resume score and match reports</p>
        </div>

        <!-- Quick Demo Credentials Banner -->
        <div class="p-4 rounded-2xl bg-purple-50 dark:bg-purple-950/60 border border-purple-200/80 dark:border-purple-800 text-xs text-purple-900 dark:text-purple-200 space-y-1">
            <p class="font-bold text-purple-800 dark:text-purple-300">💡 Quick Demo Logins:</p>
            <p><strong>Job Seeker:</strong> user@example.com / password</p>
            <p><strong>System Admin:</strong> admin@example.com / password</p>
        </div>

        <form wire:submit="login" class="space-y-6">
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

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input wire:model="remember" type="checkbox" class="rounded border-slate-300 dark:border-slate-700 text-purple-600 focus:ring-purple-500">
                    <span class="text-slate-600 dark:text-slate-400 text-xs font-medium">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:opacity-95 transition shadow-lg shadow-purple-500/25">
                Sign In
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 dark:text-slate-400">
            Don't have an account? <a href="{{ route('register') }}" class="text-purple-600 dark:text-purple-400 font-bold hover:underline">Create one free</a>
        </p>
    </div>
</div>
