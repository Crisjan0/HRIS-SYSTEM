<x-app-layout>
    <x-slot name="title">{{ __('My Compensatory Time-Off') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-black text-gray-900">{{ __('My Compensatory Time-Off') }}</h1>
            </div>

            <div class="bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                <div class="text-gray-400 mb-2">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-400 italic">
                    {{ __('No compensatory time-off records found.') }}
                </p>
                <p class="text-sm text-gray-400 mt-2">
                    {{ __('Your compensatory time-off records will appear here.') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
