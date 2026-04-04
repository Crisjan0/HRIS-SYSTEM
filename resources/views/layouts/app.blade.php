<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50/50" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">
            <!-- Off-canvas menu for mobile -->
            <div x-show="sidebarOpen" 
                 class="fixed inset-0 z-50 flex lg:hidden" 
                 x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state." 
                 role="dialog" 
                 aria-modal="true"
                 style="display: none;">
                
                <div x-show="sidebarOpen" 
                     x-transition:enter="transition-opacity ease-linear duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="transition-opacity ease-linear duration-300" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-gray-600/80 backdrop-blur-sm" 
                     aria-hidden="true"
                     @click="sidebarOpen = false"></div>

                <div x-show="sidebarOpen" 
                     x-transition:enter="transition ease-in-out duration-300 transform" 
                     x-transition:enter-start="-translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transition ease-in-out duration-300 transform" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="-translate-x-full" 
                     class="relative flex w-full max-w-xs flex-col flex-1 bg-white focus:outline-none transition-all duration-300 shadow-2xl">
                    
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" 
                                class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" 
                                @click="sidebarOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="h-full flex flex-col">
                        @include('layouts.sidebar')
                    </div>
                </div>

                <div class="w-14 shrink-0" aria-hidden="true">
                    <!-- Dummy element to force sidebar to that offset from left -->
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <aside class="hidden lg:flex lg:flex-col lg:w-72 lg:shrink-0 h-full">
                @include('layouts.sidebar')
            </aside>

            <!-- Main content area -->
            <div class="flex flex-col flex-1 w-0 overflow-hidden bg-gray-50/50">
                <!-- Mobile top bar -->
                <header class="lg:hidden flex items-center justify-between h-16 px-4 shrink-0 bg-white border-b border-gray-100 shadow-sm z-40">
                    <button type="button" 
                            class="p-2 -ml-0.5 text-gray-500 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" 
                            @click="sidebarOpen = true">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex-1 flex justify-center lg:hidden">
                        <x-application-logo class="w-10 h-10 fill-current text-indigo-600" />
                    </div>
                    <div class="flex items-center gap-2">
                        <livewire:notifications-dropdown wire:key="mobile-notifications" />
                    </div>
                </header>

                <header class="hidden lg:flex items-center justify-between h-16 px-8 shrink-0 bg-white/80 backdrop-blur-md border-b border-gray-100/50 shadow-sm z-30 sticky top-0 transition-all duration-300">
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-800 tracking-tight">
                            {{ $title ?? 'Dashboard' }}
                        </h2>
                    </div>
                    <div class="flex items-center gap-5">
                        <livewire:notifications-dropdown wire:key="desktop-notifications" />
                        
                        <div class="h-6 w-px bg-gray-100 mx-1"></div>

                        <div class="flex items-center gap-3 pl-2">
                            <div class="text-right hidden xl:block">
                                <p class="text-sm font-bold text-gray-900 leading-none mb-0.5">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-tighter">{{ Auth::user()->role ?? 'Employee' }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-sm ring-2 ring-white hover:ring-indigo-100 transition-all duration-300 cursor-pointer">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 relative overflow-y-auto focus:outline-none py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <!-- Page Heading -->
                        @isset($header)
                            <div class="mb-8">
                                {{ $header }}
                            </div>
                        @endisset

                        <!-- Page Content -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100/10 transition-all duration-300">
                             {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
