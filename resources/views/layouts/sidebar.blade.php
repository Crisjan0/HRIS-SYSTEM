<div class="flex flex-col h-full bg-white border-r border-gray-100">
    <!-- Logo -->
    <div class="px-6 py-8 flex items-center shrink-0">
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-2 group transition-all duration-300 transform hover:scale-105">
            <x-application-logo class="w-10 h-10 fill-current text-indigo-600 drop-shadow-md" />
            <span
                class="text-xl font-bold tracking-tight bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                {{ config('app.name', 'Laravel') }}
            </span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto mt-2">
        <div class="pb-4 mb-4 border-b border-gray-50">
            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                {{ __('Main Menu') }}
            </h3>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </x-slot>
                {{ __('Dashboard') }}
            </x-sidebar-link>

            @if(in_array(auth()->user()->role, ['employee', 'admin', 'hrstaff', 'director']))
                <x-sidebar-dropdown label="{{ __('My Profile') }}" :active="request()->routeIs(['pds.*', 'saln.*', 'ildp.*', 'leave.*', 'dtr.*'])">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </x-slot>

                    <x-sidebar-link :href="route('pds.index')" :active="request()->routeIs('pds.*')" class="text-xs">
                        {{ __('PDS') }}
                    </x-sidebar-link>
                    <x-sidebar-link href="#" :active="false" class="text-xs">
                        {{ __('SALN') }}
                    </x-sidebar-link>
                    <x-sidebar-link href="#" :active="false" class="text-xs">
                        {{ __('ILDP') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')" class="text-xs">
                        {{ __('Leave') }}
                    </x-sidebar-link>
                    <x-sidebar-link href="#" :active="false" class="text-xs">
                        {{ __('DTR') }}
                    </x-sidebar-link>
                </x-sidebar-dropdown>
            @endif
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'hrstaff', 'director']))
            <div class="pb-4">
                <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                    {{ __('Administration') }}
                </h3>

                <x-sidebar-dropdown :label="__('Leave & Employee')" :active="request()->routeIs(['employees.*', 'leaves.*'])">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </x-slot>

                    <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')"
                        class="text-xs">
                        {{ __('Manage Employee') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('leave-types.index')" :active="request()->routeIs('leave-types.*')"
                        class="text-xs">
                        {{ __('Manage Leave Types') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('leave-applications.index')" :active="request()->routeIs('leave-applications.index')"
                        class="text-xs">
                        {{ __('Pending Leave') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('leave-applications.all')" :active="request()->routeIs('leave-applications.all')"
                        class="text-xs">
                        {{ __('All Leave') }}
                    </x-sidebar-link>
                    <x-sidebar-link href="#" :active="false" class="text-xs">
                        {{ __('Leave Calendar') }}
                    </x-sidebar-link>
                </x-sidebar-dropdown>
            </div>
        @endif

        <div class="pb-4">
            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                {{ __('Settings') }}
            </h3>

            <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </x-slot>
                {{ __('Profile') }}
            </x-sidebar-link>

            <form method="POST" action="{{ route('logout') }}" class="group mt-auto">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span>{{ __('Log Out') }}</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- User Section -->
    <div class="px-4 py-6 border-t border-gray-100 bg-gray-50/50">
        <div class="flex items-center gap-3 px-3">
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-sm ring-2 ring-white">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</div>