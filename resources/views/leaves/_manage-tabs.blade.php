<div class="mb-6 flex flex-wrap gap-2 bg-white p-1 rounded-xl shadow-sm border border-gray-100 w-fit">
    <a href="{{ route('leave-applications.index') }}"
       class="px-6 py-2.5 text-sm font-semibold rounded-lg {{ request()->routeIs('leave-applications.index') ? 'bg-[#0038a8] text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
        {{ __('Pending Leave') }}
    </a>
    <a href="{{ route('leave-applications.all') }}"
       class="px-6 py-2.5 text-sm font-semibold rounded-lg {{ request()->routeIs('leave-applications.all') ? 'bg-[#0038a8] text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
        {{ __('All Leave Applications') }}
    </a>
    <a href="{{ route('leave-calendar') }}"
       class="px-6 py-2.5 text-sm font-semibold rounded-lg {{ request()->routeIs('leave-calendar') ? 'bg-[#0038a8] text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
        {{ __('Leave Calendar') }}
    </a>
    <a href="{{ route('leave-types.index') }}"
       class="px-6 py-2.5 text-sm font-semibold rounded-lg {{ request()->routeIs('leave-types.*') ? 'bg-[#0038a8] text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
        {{ __('Leave Types') }}
    </a>
    <a href="{{ route('holidays.index') }}"
       class="px-6 py-2.5 text-sm font-semibold rounded-lg {{ request()->routeIs('holidays.*') ? 'bg-[#0038a8] text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-all duration-200">
        {{ __('Holiday') }}
    </a>
</div>
