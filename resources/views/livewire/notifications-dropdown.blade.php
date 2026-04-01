<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <!-- Notification Bell Trigger -->
    <button @click="open = ! open" wire:poll.3s="refresh" 
            class="relative p-2 text-gray-400 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full transition-all duration-300 transform hover:scale-110 active:scale-95 bg-gray-50 hover:bg-indigo-50 shadow-sm border border-transparent hover:border-indigo-100">
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        
        @if($notifications->count() > 0)
            <!-- Notification Badge -->
            <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] items-center justify-center text-white font-bold border-2 border-white">
                    {{ $notifications->count() }}
                </span>
            </span>
        @endif
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
            @if($notifications->count() > 0)
                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-full uppercase tracking-tighter">
                    {{ $notifications->count() }} New
                </span>
            @endif
        </div>

        <!-- Notification List -->
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $type = $data['type'] ?? 'default';
                    $bgColor = match($type) {
                        'leave_request' => 'bg-blue-100',
                        'leave_status' => ($data['status'] === 'approved' ? 'bg-green-100' : 'bg-red-100'),
                        default => 'bg-gray-100'
                    };
                    $textColor = match($type) {
                        'leave_request' => 'text-blue-600',
                        'leave_status' => ($data['status'] === 'approved' ? 'text-green-600' : 'text-red-600'),
                        default => 'text-gray-600'
                    };
                    $borderColor = match($type) {
                        'leave_request' => 'border-blue-200',
                        'leave_status' => ($data['status'] === 'approved' ? 'border-green-200' : 'border-red-200'),
                        default => 'border-gray-200'
                    };
                @endphp
                <button wire:click="markAsRead('{{ $notification->id }}')" class="w-full text-left block px-4 py-4 hover:bg-indigo-50/50 transition-colors duration-200 group focus:outline-none">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-10 h-10 rounded-full {{ $bgColor }} flex items-center justify-center {{ $textColor }} group-hover:scale-110 transition-transform duration-300 shadow-sm border {{ $borderColor }}">
                            @if($type === 'leave_request')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            @elseif($type === 'leave_status')
                                @if($data['status'] === 'approved')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $data['title'] ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $data['message'] ?? '' }}</p>
                            <p class="text-[10px] text-gray-400 mt-2 flex items-center font-medium uppercase tracking-tight">
                                <svg class="w-3 h-3 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </button>
            @empty
                <div class="px-4 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500">No new notifications</p>
                    <p class="text-xs text-gray-400 mt-1">You're all caught up!</p>
                </div>
            @endforelse
        </div>

        @if($notifications->count() > 0)
            <!-- Footer -->
            <div class="px-4 py-3 bg-gray-50/80 border-t border-gray-100 text-center">
                <button wire:click="markAllAsRead" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest transition-colors duration-200 focus:outline-none">
                    {{ __('Mark all as read') }}
                </button>
            </div>
        @endif
    </div>
</div>
