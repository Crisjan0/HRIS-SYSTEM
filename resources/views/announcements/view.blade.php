<x-app-layout>
    <x-slot name="title">{{ __('Official Announcements') }}</x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 p-6 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl shadow-xl overflow-hidden relative group">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="text-white">
                        <h3 class="text-2xl font-black mb-1 italic tracking-tight uppercase">{{ __('Stay Informed!') }}</h3>
                        <p class="text-indigo-100 text-sm opacity-90 font-medium">{{ __('Get the latest news and updates from the organization.') }}</p>
                    </div>
                </div>
            </div>

            <form id="publicAnnouncementFilterForm" method="GET" action="{{ route('announcements.view') }}" class="mb-8 flex flex-col gap-2 rounded-xl border border-gray-100 bg-gray-50/70 p-2 sm:flex-row sm:items-center">
                <div class="relative min-w-0 sm:flex-1">
                    <label for="publicAnnouncementSearch" class="sr-only">{{ __('Search announcements') }}</label>
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input id="publicAnnouncementSearch" type="search" name="search" value="{{ $search }}" placeholder="{{ __('Search announcements...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" data-public-announcement-autofilter>
                </div>

                <div class="sm:w-52 sm:shrink-0">
                    <label for="publicAnnouncementMonth" class="sr-only">{{ __('Month') }}</label>
                    <select id="publicAnnouncementMonth" name="month" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all" {{ $month === 'all' ? 'selected' : '' }}>{{ __('All Months') }}</option>
                        @foreach($months as $option)
                            <option value="{{ $option['value'] }}" {{ $month === $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex shrink-0 gap-2">
                    <button type="button" id="publicAnnouncementFilterReset" class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-xs font-black uppercase tracking-widest text-gray-600 transition hover:bg-gray-50">
                        {{ __('Reset') }}
                    </button>
                </div>
            </form>

            <div id="publicAnnouncementResults">
                @include('announcements.partials.public-results', ['announcements' => $announcements])
            </div>
        </div>
    </div>

    <script>
        const publicAnnouncementFilterForm = document.getElementById('publicAnnouncementFilterForm');
        const publicAnnouncementSearchInput = document.querySelector('[data-public-announcement-autofilter]');
        const publicAnnouncementMonthSelect = document.getElementById('publicAnnouncementMonth');
        const publicAnnouncementResults = document.getElementById('publicAnnouncementResults');
        const publicAnnouncementFilterReset = document.getElementById('publicAnnouncementFilterReset');
        let publicAnnouncementSearchTimer;

        async function filterPublicAnnouncements(requestUrl = null, browserUrl = null) {
            if (!publicAnnouncementFilterForm || !publicAnnouncementResults) {
                return;
            }

            const params = new URLSearchParams(new FormData(publicAnnouncementFilterForm));
            const nextRequestUrl = requestUrl || `${publicAnnouncementFilterForm.action}?${params.toString()}`;
            const nextBrowserUrl = browserUrl || `${publicAnnouncementFilterForm.action}?${params.toString()}`;

            publicAnnouncementResults.style.opacity = '0.55';

            try {
                const response = await fetch(nextRequestUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error('Unable to filter announcements.');
                }

                const data = await response.json();
                publicAnnouncementResults.innerHTML = data.html;
                window.history.replaceState({}, '', nextBrowserUrl);
            } catch (error) {
                publicAnnouncementFilterForm.submit();
            } finally {
                publicAnnouncementResults.style.opacity = '1';
            }
        }

        if (publicAnnouncementFilterForm) {
            publicAnnouncementFilterForm.addEventListener('submit', (event) => {
                event.preventDefault();
                filterPublicAnnouncements();
            });
        }

        if (publicAnnouncementSearchInput) {
            publicAnnouncementSearchInput.addEventListener('input', () => {
                clearTimeout(publicAnnouncementSearchTimer);
                publicAnnouncementSearchTimer = setTimeout(() => filterPublicAnnouncements(), 600);
            });
        }

        if (publicAnnouncementMonthSelect) {
            publicAnnouncementMonthSelect.addEventListener('change', () => filterPublicAnnouncements());
        }

        if (publicAnnouncementFilterReset) {
            publicAnnouncementFilterReset.addEventListener('click', () => {
                if (publicAnnouncementSearchInput) {
                    publicAnnouncementSearchInput.value = '';
                }

                if (publicAnnouncementMonthSelect) {
                    publicAnnouncementMonthSelect.value = 'all';
                }

                filterPublicAnnouncements();
            });
        }

        if (publicAnnouncementResults) {
            publicAnnouncementResults.addEventListener('click', (event) => {
                const link = event.target.closest('a');

                if (!link || !link.href || !link.closest('nav')) {
                    return;
                }

                event.preventDefault();
                const url = new URL(link.href);
                filterPublicAnnouncements(`${publicAnnouncementFilterForm.action}${url.search}`, `${publicAnnouncementFilterForm.action}${url.search}`);
            });
        }
    </script>

    <style>
        .shadow-special {
            box-shadow: 0 20px 50px -12px rgba(99, 102, 241, 0.1);
        }
    </style>
</x-app-layout>
