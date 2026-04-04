<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Welcome Section -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 p-8">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-indigo-200">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">{{ __('Welcome back,') }} {{ Auth::user()->name }}!</h3>
                                <p class="text-gray-500 font-medium whitespace-nowrap">{{ __("You are officially logged in to the HRIS ecosystem.") }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Dashboard Content can go here -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-indigo-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-100 relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                            <h4 class="text-xs font-black uppercase tracking-widest opacity-80 mb-1">{{ __('My Profile') }}</h4>
                            <p class="text-xl font-bold mb-4 tracking-tight">{{ __('View PDS & SALN') }}</p>
                            <a href="{{ route('pds.index') }}" class="inline-flex items-center text-xs font-black bg-white text-indigo-600 px-4 py-2 rounded-xl uppercase tracking-widest hover:bg-indigo-50 transition-colors">
                                {{ __('Go to Profile') }}
                            </a>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Quick Action') }}</h4>
                            <p class="text-xl font-bold text-gray-900 mb-4 tracking-tight uppercase tracking-widest">{{ __('Apply for Leave') }}</p>
                            <a href="{{ route('leaves.index') }}" class="inline-flex items-center text-xs font-black text-indigo-600 uppercase tracking-widest gap-1 group">
                                {{ __('New Request') }}
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Latest Announcement Sidebar -->
                <div class="lg:col-span-1">
                    @if($latestAnnouncement)
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-indigo-50 border border-indigo-50/50 flex flex-col h-full relative group overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full blur-3xl -mr-16 -mt-16"></div>
                            
                            <div class="flex items-center justify-between mb-8 relative z-10">
                                <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full uppercase tracking-widest border border-indigo-100">
                                    {{ __('Breaking News') }}
                                </span>
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                            </div>

                            <div class="relative z-10 flex-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ $latestAnnouncement->published_at->diffForHumans() }}</p>
                                <h3 class="text-2xl font-black text-gray-900 mb-4 tracking-tight leading-7 line-clamp-3 uppercase tracking-tighter">
                                    {{ $latestAnnouncement->title }}
                                </h3>
                                <div class="text-sm text-gray-600 font-medium leading-relaxed line-clamp-6 mb-8">
                                    {{ Str::limit(strip_tags($latestAnnouncement->content), 250) }}
                                </div>
                            </div>

                            <div class="relative z-10 mt-auto">
                                <a href="{{ route('announcements.show', $latestAnnouncement) }}" class="flex items-center justify-center w-full px-6 py-4 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-gray-200">
                                    {{ __('Read Full Article') }}
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('announcements.view') }}" class="block text-center mt-4 text-[10px] font-black text-gray-400 hover:text-indigo-600 uppercase tracking-widest transition-colors">
                                    {{ __('View All Announcements') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2.5rem] p-12 text-center flex flex-col items-center justify-center h-full">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ __('No new announcements') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
