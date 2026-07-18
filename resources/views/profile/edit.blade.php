<x-app-layout>
    <x-slot name="title">{{ __('Profile') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(auth()->user()?->must_change_password)
                <div class="p-4 sm:p-6 bg-amber-50 border border-amber-200 shadow sm:rounded-lg">
                    <h2 class="text-base font-bold text-amber-900">{{ __('Temporary Password Must Be Changed') }}</h2>
                    <p class="mt-1 text-sm text-amber-800">{{ __('For your account security, please update your password before continuing to use the system.') }}</p>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
