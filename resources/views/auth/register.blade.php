<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="relative mb-5">
            <x-text-input id="name" class="capitalize block w-full px-4 pb-2.5 pt-6 text-[17px] !rounded-lg border-gray-300 dark:border-gray-600 focus:border-[#0038a8] focus:ring-[#0038a8] peer placeholder-transparent" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Full Name" />
            <label for="name" class="absolute text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3.5 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-[#0038a8] peer-focus:dark:text-[#5c8aff] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3.5 pointer-events-none text-[17px]">
                Full Name
            </label>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="relative mb-5">
            <x-text-input id="email" class="block w-full px-4 pb-2.5 pt-6 text-[17px] !rounded-lg border-gray-300 dark:border-gray-600 focus:border-[#0038a8] focus:ring-[#0038a8] peer placeholder-transparent" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email Address" />
            <label for="email" class="absolute text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3.5 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-[#0038a8] peer-focus:dark:text-[#5c8aff] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3.5 pointer-events-none text-[17px]">
                Email Address
            </label>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative mb-5">
            <x-text-input id="password" class="block w-full px-4 pb-2.5 pt-6 text-[17px] !rounded-lg border-gray-300 dark:border-gray-600 focus:border-[#0038a8] focus:ring-[#0038a8] peer placeholder-transparent pr-14"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="New Password" />
            <label for="password" class="absolute text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3.5 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-[#0038a8] peer-focus:dark:text-[#5c8aff] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3.5 pointer-events-none text-[17px]">
                New Password
            </label>
            <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerText = p.type === 'password' ? 'Show' : 'Hide';" class="absolute right-4 top-[18px] text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none peer-placeholder-shown:hidden">Show</button>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="relative mb-5">
            <x-text-input id="password_confirmation" class="block w-full px-4 pb-2.5 pt-6 text-[17px] !rounded-lg border-gray-300 dark:border-gray-600 focus:border-[#0038a8] focus:ring-[#0038a8] peer placeholder-transparent pr-14"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Confirm Password" />
            <label for="password_confirmation" class="absolute text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3.5 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-[#0038a8] peer-focus:dark:text-[#5c8aff] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3.5 pointer-events-none text-[17px]">
                Confirm Password
            </label>
            <button type="button" onclick="const p = document.getElementById('password_confirmation'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerText = p.type === 'password' ? 'Show' : 'Hide';" class="absolute right-4 top-[18px] text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none peer-placeholder-shown:hidden">Show</button>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <p id="password_match_message" class="text-sm mt-[-10px] mb-4 hidden"></p>

        <div class="flex flex-col items-center justify-center mt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-[#0038a8] border border-transparent rounded-lg font-bold text-base text-white hover:bg-[#002a7a] focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:ring-offset-2 transition ease-in-out duration-150 shadow-md mb-4">
                {{ __('Register') }}
            </button>

            <a class="text-sm font-medium text-[#0038a8] dark:text-[#5c8aff] hover:underline" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>
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
                message.className = 'text-sm mt-[-10px] mb-3 text-[#42b72a] dark:text-[#42b72a] font-medium';
            } else {
                message.textContent = 'Passwords do not match';
                message.className = 'text-sm mt-[-10px] mb-3 text-[#ce1126] dark:text-[#ff4757] font-medium';
            }
        }
        
        document.getElementById('password').addEventListener('input', checkPasswordsMatch);
        document.getElementById('password_confirmation').addEventListener('input', checkPasswordsMatch);
    </script>
</x-guest-layout>
