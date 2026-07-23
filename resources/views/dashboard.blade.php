<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-3">
                <!-- Welcome Section -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 p-8">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">{{ __('Welcome back,') }} {{ Auth::user()->display_name }}!</h3>
                        </div>
                    </div>

                    <!-- Additional Dashboard Content can go here -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-900 rounded-3xl p-6 text-white shadow-xl shadow-blue-200 relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                            <h4 class="text-xs font-black uppercase tracking-widest opacity-80 mb-1">{{ __('My Profile') }}</h4>
                            <p class="text-xl font-bold mb-4 tracking-tight">{{ __('View PDS & SALN') }}</p>
                            <a href="{{ route('pds.index') }}" class="inline-flex items-center text-xs font-black bg-white text-blue-900 px-4 py-2 rounded-xl uppercase tracking-widest hover:bg-blue-100 transition-colors">
                                {{ __('Go to Profile') }}
                            </a>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Quick Action') }}</h4>
                            <p class="text-xl font-bold text-gray-900 mb-4 tracking-tight uppercase tracking-widest">{{ __('Apply for Leave') }}</p>
                            <a href="{{ route('leaves.index') }}" class="inline-flex items-center text-xs font-black text-blue-900 uppercase tracking-widest gap-1 group">
                                {{ __('New Request') }}
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    @if($employee)
                        <div id="dashboardLeaveCalendar" data-calendar-container>
                            @include('dashboard._leave-calendar')
                        </div>
                    @endif
                </div>

                <!-- Latest Announcement Sidebar -->
                <div class="lg:col-span-1 lg:self-start">
                    @if(in_array(auth()->user()->role, ['admin', 'hrstaff', 'regionaldirector']))
                        <div class="mb-3 flex justify-end">
                            <a href="{{ route('announcements.index', ['openCreate' => 1]) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-indigo-100 transition hover:bg-indigo-700">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                                </svg>
                                {{ __('New Announcement') }}
                            </a>
                        </div>
                    @endif

                    @if($latestAnnouncement)
                        <div class="bg-white rounded-[2.5rem] p-6 shadow-2xl shadow-indigo-50 border border-indigo-50/50 flex flex-col relative group overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full blur-3xl -mr-16 -mt-16"></div>
                            
                            <div class="flex items-center justify-between mb-4 relative z-10">
                                <span class="inline-flex w-full items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50 px-5 py-3 text-xs font-black uppercase tracking-widest text-indigo-600">
                                    {{ __('Latest Updates') }}
                                </span>
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                            </div>

                            <div class="relative z-10">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ $latestAnnouncement->published_at->diffForHumans() }}</p>
                                <h3 class="text-xl font-black text-gray-900 mb-3 tracking-tight leading-6 line-clamp-2 uppercase tracking-tighter">
                                    {{ $latestAnnouncement->title }}
                                </h3>
                                <div class="text-sm text-gray-600 font-medium leading-relaxed line-clamp-3 mb-4">
                                    {{ Str::limit(strip_tags($latestAnnouncement->content), 160) }}
                                </div>
                            </div>

                            <div class="relative z-10">
                                <a href="{{ route('announcements.show', $latestAnnouncement) }}" class="flex items-center justify-center w-full px-5 py-3 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-gray-200">
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
    <script>
        document.addEventListener('click', async (event) => {
            const link = event.target.closest('[data-calendar-link]');
            const container = document.querySelector('[data-calendar-container]');

            if (!link || !container) {
                return;
            }

            event.preventDefault();

            try {
                const response = await fetch(link.dataset.calendarUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    window.location.href = link.href;
                    return;
                }

                const payload = await response.json();
                container.innerHTML = payload.html;
                window.history.replaceState({}, '', link.href);
            } catch (error) {
                window.location.href = link.href;
            }
        });
    </script>
</x-app-layout>
