<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" x-data="{ newPassword: '', confirmPassword: '' }">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full pr-14" autocomplete="current-password" />
                <button type="button" onclick="const p = document.getElementById('update_password_current_password'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerText = p.type === 'password' ? 'Show' : 'Hide';" class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-semibold text-gray-500 hover:text-[#0038a8] focus:outline-none transition-colors">Show</button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full pr-14" autocomplete="new-password" x-model="newPassword" />
                <button type="button" onclick="const p = document.getElementById('update_password_password'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerText = p.type === 'password' ? 'Show' : 'Hide';" class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-semibold text-gray-500 hover:text-[#0038a8] focus:outline-none transition-colors">Show</button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full pr-14" autocomplete="new-password" x-model="confirmPassword" />
                <button type="button" onclick="const p = document.getElementById('update_password_password_confirmation'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerText = p.type === 'password' ? 'Show' : 'Hide';" class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-semibold text-gray-500 hover:text-[#0038a8] focus:outline-none transition-colors">Show</button>
            </div>

            <div x-show="confirmPassword.length > 0 && newPassword === confirmPassword" style="display: none;" class="mt-2 text-sm font-bold text-green-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Passwords match!
            </div>
            <div x-show="confirmPassword.length > 0 && newPassword !== confirmPassword" style="display: none;" class="mt-2 text-sm font-bold text-red-500 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Passwords do not match
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
