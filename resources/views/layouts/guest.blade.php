<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DMW HRIS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes pulseGlow {
                0%, 100% { box-shadow: 0 0 0 0 rgba(252, 209, 22, 0.4); }
                50% { box-shadow: 0 0 0 15px rgba(252, 209, 22, 0); }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 dark:bg-gray-900 selection:bg-[#0038a8] selection:text-white">
        
        <!-- Decorative Background Elements -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <!-- Blue Gradient Blob -->
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-[#0038a8] opacity-5 dark:opacity-20 blur-3xl mix-blend-multiply"></div>
            <!-- Red Gradient Blob -->
            <div class="absolute -bottom-[20%] -right-[10%] w-[50%] h-[50%] rounded-full bg-[#ce1126] opacity-5 dark:opacity-20 blur-3xl mix-blend-multiply"></div>
            <!-- Yellow Gradient Blob -->
            <div class="absolute top-[20%] right-[20%] w-[30%] h-[30%] rounded-full bg-[#fcd116] opacity-5 dark:opacity-10 blur-3xl mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <div class="mb-6 mt-12 sm:mt-0">
                <a href="/" class="block relative w-32 h-32 md:w-40 md:h-40 group">
                    <!-- Outer Rotating Dashed Ring -->
                    <div class="absolute inset-0 rounded-full border-2 border-dashed border-[#fcd116]/60 dark:border-[#fcd116]/30" style="animation: spin 20s linear infinite;"></div>
                    <!-- Middle Solid Ring -->
                    <div class="absolute inset-1.5 rounded-full border border-[#0038a8]/20 dark:border-[#0038a8]/50"></div>
                    <!-- Inner Red Ring -->
                    <div class="absolute inset-2.5 rounded-full border-2 border-[#ce1126]/10 dark:border-[#ce1126]/30"></div>
                    
                    <!-- Logo Container -->
                    <div class="absolute inset-4 rounded-full bg-white shadow-xl flex items-center justify-center overflow-hidden z-10 transition-transform duration-500 group-hover:scale-105" style="animation: pulseGlow 4s infinite ease-in-out;">
                        <x-application-logo class="w-full h-full object-cover rounded-full" />
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] overflow-hidden sm:rounded-2xl border border-white/50 dark:border-gray-700/50">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} Department of Migrant Workers Philippines.
            </div>
        </div>
    </body>
</html>
