@props(['active', 'icon' => null])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-3 py-1.5 text-sm font-medium rounded-lg bg-[#D8ECFF] text-blue-900 transition duration-150 ease-in-out group'
            : 'flex items-center gap-3 px-3 py-1.5 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition duration-150 ease-in-out group';

$iconClasses = ($active ?? false)
            ? 'w-5 h-5 text-blue-600'
            : 'w-5 h-5 text-gray-400 group-hover:text-gray-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} :class="sidebarCollapsed ? 'justify-center' : ''">
    @if($icon)
        <span class="{{ $iconClasses }}">
            {{ $icon }}
        </span>
    @endif
    
    <span class="truncate"  x-show="!sidebarCollapsed" x-transition>
        {{ $slot }}
    </span>
</a>
