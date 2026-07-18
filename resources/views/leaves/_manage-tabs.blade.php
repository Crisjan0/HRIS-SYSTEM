<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex gap-8 overflow-x-auto" aria-label="Tabs">
        <a href="{{ route('leave-applications.index') }}"
           class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-bold uppercase tracking-widest transition-colors duration-200 {{ request()->routeIs('leave-applications.index') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
            {{ __('Pending Leave') }}
        </a>
        <a href="{{ route('leave-applications.all') }}"
           class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-bold uppercase tracking-widest transition-colors duration-200 {{ request()->routeIs('leave-applications.all') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
            {{ __('All Leave Applications') }}
        </a>
    </nav>
</div>
