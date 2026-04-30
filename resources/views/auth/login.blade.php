<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Please sign in to your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full pl-10 pr-3 py-2.5 border border-gray-400 dark:border-gray-500 rounded-lg shadow-sm bg-white dark:bg-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:border-[#0038a8] dark:text-white sm:text-sm transition-colors duration-200" placeholder="you@example.com" value="{{ old('email') }}" autofocus>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full pl-10 pr-10 py-2.5 border border-gray-400 dark:border-gray-500 rounded-lg shadow-sm bg-white dark:bg-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:border-[#0038a8] dark:text-white sm:text-sm transition-colors duration-200" placeholder="••••••••">
                
                <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-[#0038a8] focus:ring-[#0038a8] border-gray-300 rounded dark:border-gray-600 dark:bg-gray-800 transition-colors">
                <label for="remember_me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                    Remember me
                </label>
            </div>

            @if (Route::has('password.request'))
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-[#0038a8] dark:text-[#5c8aff] hover:text-[#002a7a] dark:hover:text-[#8aaaff] hover:underline transition-colors">
                        Forgot your password?
                    </a>
                </div>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-[#0038a8] hover:bg-[#002a7a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0038a8] transition-all duration-200 hover:scale-[1.02]">
                Sign in
                <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>

        @if (Route::has('register'))
            <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-[#0038a8] dark:text-[#5c8aff] hover:text-[#002a7a] dark:hover:text-[#8aaaff] hover:underline transition-colors">
                    Register now
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
