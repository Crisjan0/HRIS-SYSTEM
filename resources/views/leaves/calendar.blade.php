<x-app-layout>
    <x-slot name="title">{{ __('Leave Calendar') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('leaves._manage-tabs')

            <livewire:leave-calendar />
        </div>
    </div>
</x-app-layout>
