@props(['active' => false, 'label', 'icon' => null])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="space-y-1">
    <button @click="open = !open" 
            type="button" 
            class="w-full flex items-center justify-between gap-3 px-3 py-1.5 text-sm font-medium rounded-lg transition duration-150 ease-in-out group {{ $active ? 'bg-[#D8ECFF] text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        
        <div class="flex items-center gap-3">
            @if($icon)
                <span class="{{ $active ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}">
                    {{ $icon }}
                </span>
            @endif
           <span class="truncate" x-show="!sidebarCollapsed" x-transition>{{ $label }}</span>
        </div>

        <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200"
             :class="{ 'rotate-180': open }" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- This prevents dropdown child links from appearing awkwardly when collapsed. -->
    <div x-show="open && !sidebarCollapsed" 

         class="pl-10 space-y-1" 
         style="display: none;">
        {{ $slot }}
    </div>
</div>
