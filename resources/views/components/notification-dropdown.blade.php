<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <!-- Notification Bell Trigger -->
    <button @click="open = ! open" 
            class="relative p-2 text-gray-400 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full transition-all duration-300 transform hover:scale-110 active:scale-95 bg-gray-50 hover:bg-indigo-50 shadow-sm border border-transparent hover:border-indigo-100">
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        
        <!-- Notification Badge -->
        <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] items-center justify-center text-white font-bold border-2 border-white">
                3
            </span>
        </span>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-[-10px]"
         class="absolute right-0 z-50 mt-3 w-80 origin-top-right rounded-2xl bg-white/95 backdrop-blur-md shadow-2xl ring-1 ring-black/5 focus:outline-none border border-gray-100 overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-4 py-3 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest">{{ __('Notifications') }}</h3>
            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-full uppercase tracking-tighter">
                3 New
            </span>
        </div>

        <!-- Notification List -->
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
            <!-- Mock Notification 1 -->
            <a href="#" class="block px-4 py-4 hover:bg-indigo-50/50 transition-colors duration-200 group">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300 shadow-sm border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 leading-tight">New Leave Request</p>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">Juan Dela Cruz submitted a Sick Leave for April 10-12, 2026.</p>
                        <p class="text-[10px] text-gray-400 mt-2 flex items-center font-medium uppercase tracking-tight">
                            <svg class="w-3 h-3 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            2 minutes ago
                        </p>
                    </div>
                </div>
            </a>

            <!-- Mock Notification 2 -->
            <a href="#" class="block px-4 py-4 hover:bg-orange-50/50 transition-colors duration-200 group">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform duration-300 shadow-sm border border-orange-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 leading-tight">Balance Threshold</p>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">Your Sick Leave balance is below 5 days. Consider replenishment.</p>
                        <p class="text-[10px] text-gray-400 mt-2 flex items-center font-medium uppercase tracking-tight">
                            <svg class="w-3 h-3 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            1 hour ago
                        </p>
                    </div>
                </div>
            </a>

            <!-- Mock Notification 3 -->
            <a href="#" class="block px-4 py-4 hover:bg-green-50/50 transition-colors duration-200 group">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform duration-300 shadow-sm border border-green-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 leading-tight">Leave Approved</p>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">Your vacation leave request for April 5-8 has been approved.</p>
                        <p class="text-[10px] text-gray-400 mt-2 flex items-center font-medium uppercase tracking-tight">
                            <svg class="w-3 h-3 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Yesterday
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50/80 border-t border-gray-100 text-center">
            <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest transition-colors duration-200">
                View All Notifications
            </a>
        </div>
    </div>
</div>
