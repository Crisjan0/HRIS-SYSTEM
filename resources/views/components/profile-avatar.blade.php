@props([
    'user' => null,
    'employee' => null,
    'name' => null,
    'size' => 'md',
    'variant' => 'default',
    'rounded' => 'full',
])

@php
    $employee = $employee ?? $user?->employee;
    $pictureUrl = $employee?->profile_picture_url ?? null;
    $initials = $employee?->initials ?? strtoupper(substr($name ?? $user?->display_name ?? '?', 0, 1));
    $alt = $name ?? $user?->display_name ?? trim(($employee->firstname ?? '') . ' ' . ($employee->lastname ?? '')) ?: 'Profile';

    $sizes = [
        'xs' => 'w-6 h-6 text-[10px]',
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-lg',
        'xl' => 'w-16 h-16 text-xl',
        '2xl' => 'w-20 h-20 text-3xl',
        '3xl' => 'w-24 h-24 text-3xl',
        '4xl' => 'w-32 h-32 text-4xl',
    ];

    $sizeClasses = $sizes[$size] ?? $size;

    $variants = [
        'default' => 'bg-gradient-to-br from-indigo-500 to-purple-600',
        'indigo' => 'bg-indigo-500',
        'amber' => 'bg-amber-500',
        'green' => 'bg-green-500',
        'brand' => 'bg-gradient-to-br from-[#0038a8] to-[#ce1126]',
        'ghost' => 'bg-white/10 text-indigo-100',
    ];

    $roundedClass = $rounded === '2xl' ? 'rounded-2xl' : 'rounded-full';
    $fallbackClass = $variants[$variant] ?? $variants['default'];
@endphp

<div {{ $attributes->merge([
    'class' => trim("{$sizeClasses} {$roundedClass} shrink-0 overflow-hidden flex items-center justify-center font-bold text-white uppercase {$fallbackClass}"),
]) }}>
    @if($pictureUrl)
        <img src="{{ $pictureUrl }}" alt="{{ $alt }}" class="w-full h-full object-cover">
    @else
        {{ $initials }}
    @endif
</div>
