<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create an account</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Join us to get started</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input id="name" name="name" type="text" autocomplete="name" required autofocus class="appearance-none block w-full pl-10 pr-3 py-2.5 border border-gray-400 dark:border-gray-500 rounded-lg shadow-sm bg-white dark:bg-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:border-[#0038a8] dark:text-white sm:text-sm transition-colors duration-200 capitalize" placeholder="John Doe" value="{{ old('name') }}">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

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
                <input id="email" name="email" type="email" autocomplete="username" required class="appearance-none block w-full pl-10 pr-3 py-2.5 border border-gray-400 dark:border-gray-500 rounded-lg shadow-sm bg-white dark:bg-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:border-[#0038a8] dark:text-white sm:text-sm transition-colors duration-200" placeholder="you@example.com" value="{{ old('email') }}">
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
                <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none block w-full pl-10 pr-10 py-2.5 border border-gray-400 dark:border-gray-500 rounded-lg shadow-sm bg-white dark:bg-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:border-[#0038a8] dark:text-white sm:text-sm transition-colors duration-200" placeholder="••••••••">
                
                <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="appearance-none block w-full pl-10 pr-10 py-2.5 border border-gray-400 dark:border-gray-500 rounded-lg shadow-sm bg-white dark:bg-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:border-[#0038a8] dark:text-white sm:text-sm transition-colors duration-200" placeholder="••••••••">
                
                <button type="button" onclick="const p = document.getElementById('password_confirmation'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <p id="password_match_message" class="text-sm mt-2 hidden"></p>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-[#0038a8] hover:bg-[#002a7a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0038a8] transition-all duration-200 hover:scale-[1.02]">
                Register
                <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </button>
        </div>

        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            Already registered?
            <a href="{{ route('login') }}" class="font-medium text-[#0038a8] dark:text-[#5c8aff] hover:text-[#002a7a] dark:hover:text-[#8aaaff] hover:underline transition-colors">
                Log in instead
            </a>
        </p>
    </form>

    <script>
        function checkPasswordsMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const message = document.getElementById('password_match_message');
            
            if (confirm === '') {
                message.classList.add('hidden');
                return;
            }
            
            message.classList.remove('hidden');
            if (password === confirm) {
                message.innerHTML = 'Passwords match <span class=\"font-bold\">✓</span>';
                message.className = 'text-sm mt-2 text-[#42b72a] dark:text-[#42b72a] font-medium';
            } else {
                message.textContent = 'Passwords do not match';
                message.className = 'text-sm mt-2 text-[#ce1126] dark:text-[#ff4757] font-medium';
            }
        }
        
        document.getElementById('password').addEventListener('input', checkPasswordsMatch);
        document.getElementById('password_confirmation').addEventListener('input', checkPasswordsMatch);
    </script>
</x-guest-layout>
