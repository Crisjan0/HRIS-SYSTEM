<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="relative mb-5">
            <x-text-input id="email" class="block w-full px-4 pb-2.5 pt-6 text-[17px] !rounded-lg border-gray-300 dark:border-gray-600 focus:border-[#0038a8] focus:ring-[#0038a8] peer placeholder-transparent" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email address" />
            <label for="email" class="absolute text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3.5 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-[#0038a8] peer-focus:dark:text-[#5c8aff] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3.5 pointer-events-none text-[17px]">
                Email address
            </label>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative mb-5">
            <x-text-input id="password" class="block w-full px-4 pb-2.5 pt-6 text-[17px] !rounded-lg border-gray-300 dark:border-gray-600 focus:border-[#0038a8] focus:ring-[#0038a8] peer placeholder-transparent pr-14"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="Password" />
            <label for="password" class="absolute text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3.5 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-[#0038a8] peer-focus:dark:text-[#5c8aff] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3.5 pointer-events-none text-[17px]">
                Password
            </label>
            <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerText = p.type === 'password' ? 'Show' : 'Hide';" class="absolute right-4 top-[18px] text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none peer-placeholder-shown:hidden">Show</button>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Log In Button -->
        <div class="mt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-[#0038a8] border border-transparent rounded-lg font-bold text-base text-white hover:bg-[#002a7a] focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                {{ __('Log in') }}
            </button>
        </div>

        <!-- Forgot Password -->
        <div class="mt-4 text-center">
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-[#0038a8] dark:text-[#5c8aff] hover:underline" href="{{ route('password.request') }}">
                    {{ __('Forgotten password?') }}
                </a>
            @endif
        </div>

        <div class="my-5 border-b border-gray-300 dark:border-gray-600"></div>

        <!-- Register Button (Facebook's "Create new account" style) -->
        <div class="text-center pb-1">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-4 py-2.5 bg-[#42b72a] border border-transparent rounded-lg font-bold text-sm text-white hover:bg-[#36a420] focus:outline-none focus:ring-2 focus:ring-[#42b72a] focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    {{ __('Create new account') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
