<x-app-layout>
    <x-slot name="title">{{ __('Leave Calendar') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs Navigation -->
            <div class="mb-6 flex space-x-2 bg-white p-1 rounded-xl shadow-sm border border-gray-100 w-fit">
                <a href="{{ route('leave-applications.index') }}" class="px-6 py-2.5 text-sm font-semibold rounded-lg {{ request()->routeIs('leave-applications.index') ? 'bg-[#0038a8] text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                    Pending Leave
                </a>
                <a href="{{ route('leave-applications.all') }}" class="px-6 py-2.5 text-sm font-semibold rounded-lg {{ request()->routeIs('leave-applications.all') ? 'bg-[#0038a8] text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                    All Leave Applications
                </a>
                <a href="{{ route('leave-calendar') }}" class="px-6 py-2.5 text-sm font-semibold rounded-lg {{ request()->routeIs('leave-calendar') ? 'bg-[#0038a8] text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
                    Leave Calendar
                </a>
            </div>

            <livewire:leave-calendar />
        </div>
    </div>
</x-app-layout>
