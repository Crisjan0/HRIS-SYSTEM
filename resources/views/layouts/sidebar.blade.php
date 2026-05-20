<div class="flex flex-col h-full bg-white/80 backdrop-blur-xl relative overflow-hidden border-r border-gray-100">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden opacity-80">
        <!-- Blue Gradient Blob -->
        <div class="absolute -top-10 -left-10 w-48 h-48 rounded-full bg-[#0038a8] opacity-[0.08] blur-2xl"></div>
        <!-- Yellow Gradient Blob -->
        <div class="absolute top-[35%] -right-10 w-32 h-32 rounded-full bg-[#fcd116] opacity-[0.12] blur-xl"></div>
        <!-- Red Gradient Blob -->
        <div class="absolute bottom-10 -left-10 w-40 h-40 rounded-full bg-[#ce1126] opacity-[0.08] blur-2xl"></div>
    </div>

    <!-- Main Content Container -->
    <div class="relative z-10 flex flex-col h-full">

        <!-- Logo -->
        <div class="px-6 py-8 flex items-center shrink-0">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 group transition-all duration-300 transform hover:scale-105">
                <x-application-logo class="w-10 h-10 fill-current text-[#0038a8] drop-shadow-md" />
                <span
                    class="text-xl font-black tracking-tight bg-gradient-to-r from-[#0038a8] to-[#ce1126] bg-clip-text text-transparent">
                    DMW HRIS
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

            <x-sidebar-link :href="route('announcements.view')" :active="request()->routeIs('announcements.view')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                        </path>
                    </svg>
                </x-slot>
                {{ __('Announcements') }}
            </x-sidebar-link>

            @if(in_array(auth()->user()->role, ['employee', 'admin', 'hrstaff', 'director', 'chief', 'regionaldirector', 'regional director']))
                <x-sidebar-dropdown label="{{ __('My Profile') }}" :active="request()->routeIs(['pds.*', 'saln.*', 'ildp.*', 'leaves.*', 'my-dtr.*', 'locator-slips.*'])">
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
                    <x-sidebar-link :href="route('locator-slips.index')" :active="request()->routeIs('locator-slips.*')" class="text-xs">
                        {{ __('Locator Slip') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')" class="text-xs">
                        {{ __('Leave') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('my-dtr.index')" :active="request()->routeIs('my-dtr.*')" class="text-xs">
                        {{ __('Attendance / DTR') }}
                    </x-sidebar-link>
                </x-sidebar-dropdown>
            @endif
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'hrstaff', 'director', 'chief', 'regionaldirector', 'regional director']))
            <div class="pb-4">
                <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                    {{ __('Administration') }}
                </h3>

                <x-sidebar-dropdown :label="__('Manage Locator Slip')" :active="request()->routeIs(['hr.locator-slips.all', 'hr.locator-slips.pending'])">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v11.494m-9-5.747h18"></path>
                        </svg>
                    </x-slot>

                    <x-sidebar-link :href="route('hr.locator-slips.all')" :active="request()->routeIs('hr.locator-slips.all')" class="text-xs">
                        {{ __('All Locator Slips') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('hr.locator-slips.pending')" :active="request()->routeIs('hr.locator-slips.pending')" class="text-xs">
                        {{ __('Pending Locator Slips') }}
                    </x-sidebar-link>
                </x-sidebar-dropdown>

                <x-sidebar-dropdown :label="__('Leave & Employee')" :active="request()->routeIs(['employees.*', 'leave-types.*', 'leave-applications.*', 'leave-calendar', 'holidays.*'])">
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
                    <x-sidebar-link :href="route('holidays.index')" :active="request()->routeIs('holidays.*')"
                        class="text-xs">
                        {{ __('Manage Holiday') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('leave-applications.index')"
                        :active="request()->routeIs('leave-applications.index')" class="text-xs">
                        {{ __('Pending Leave') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('leave-applications.all')"
                        :active="request()->routeIs('leave-applications.all')" class="text-xs">
                        {{ __('All Leave') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('leave-calendar')" :active="request()->routeIs('leave-calendar')" class="text-xs">
                        {{ __('Leave Calendar') }}
                    </x-sidebar-link>
                </x-sidebar-dropdown>

                <x-sidebar-dropdown :label="__('Announcement')" :active="request()->routeIs(['announcements.index', 'announcements.create', 'announcements.edit', 'announcements.show'])">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                    </x-slot>

                    <x-sidebar-link :href="route('announcements.index')" :active="request()->routeIs('announcements.index')"
                        class="text-xs">
                        {{ __('Manage Announcement') }}
                    </x-sidebar-link>
                </x-sidebar-dropdown>

                <x-sidebar-dropdown :label="__('Attendance')" :active="request()->routeIs('dtr.*')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot>

                    <x-sidebar-link :href="route('dtr.index')" :active="request()->routeIs('dtr.*')" class="text-xs">
                        {{ __('Manage DTR') }}
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
        <div class="px-4 py-6 border-t border-white/40 bg-white/40 backdrop-blur-sm">
            <div class="flex items-center gap-3 px-3 group cursor-pointer">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-[#0038a8] to-[#ce1126] flex items-center justify-center text-white font-bold shadow-md shadow-[#0038a8]/20 ring-2 ring-white transition-transform duration-300 group-hover:scale-110">
                    {{ substr(Auth::user()->display_name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->display_name }}</p>
                    <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div> <!-- /End relative z-10 container -->
</div>