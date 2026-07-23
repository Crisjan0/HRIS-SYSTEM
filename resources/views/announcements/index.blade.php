<x-app-layout>
    <x-slot name="title">{{ __('Manage Announcements') }}</x-slot>

    @php
        $role = strtolower(auth()->user()->role ?? '');
        $canPost = in_array($role, ['admin', 'hrstaff', 'regionaldirector']);
        $categoryColor = function (?string $tag): string {
            return match (trim($tag ?? 'General')) {
                'Meeting' => 'bg-blue-100 text-blue-700',
                'Training', 'Workshop' => 'bg-orange-100 text-orange-700',
                'Memo' => 'bg-yellow-100 text-yellow-700',
                'Office Orders' => 'bg-slate-100 text-slate-700',
                'Advisory' => 'bg-emerald-100 text-emerald-700',
                default => 'bg-indigo-50 text-indigo-700',
            };
        };
    @endphp

    <div
        class="py-12"
        x-data="{ openAnnouncement: {{ request()->boolean('openCreate') ? 'true' : 'false' }} }"
        x-cloak
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-r-lg shadow-sm flex items-center justify-between" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-600" type="button">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                    {{ __('Please check the highlighted announcement fields and try again.') }}
                </div>
            @endif

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <label for="announcementYear" class="sr-only">{{ __('Year') }}</label>
                    <select form="announcementFilterForm" id="announcementYear" name="year" class="block h-10 rounded-lg border-gray-300 bg-white text-sm font-semibold text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($years as $yearOption)
                            <option value="{{ $yearOption }}" {{ (int) $year === (int) $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                        @endforeach
                    </select>
                </div>
                @if($canPost)
                    <button
                        type="button"
                        @click="openAnnouncement = true"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-indigo-100 transition hover:bg-indigo-700 sm:w-auto"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        {{ __('New Announcement') }}
                    </button>
                @endif
            </div>

            <div class="border-b border-gray-200">
                <div class="flex gap-8 overflow-x-auto">
                    <a
                        href="{{ route('announcements.index', array_filter(['search' => $search, 'category' => $category !== 'all' ? $category : null, 'year' => $year, 'month' => $month !== 'all' ? $month : null, 'sort' => $sort !== 'latest' ? $sort : null, 'status' => 'all'])) }}"
                        data-announcement-tab
                        data-status="all"
                        data-mine="0"
                        class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-bold uppercase tracking-[0.14em] transition {{ $status === 'all' && !$mine ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
                    >
                        {{ __('All Announcements') }}
                    </a>
                    <a
                        href="{{ route('announcements.index', array_filter(['search' => $search, 'category' => $category !== 'all' ? $category : null, 'year' => $year, 'month' => $month !== 'all' ? $month : null, 'sort' => $sort !== 'latest' ? $sort : null, 'status' => $status, 'mine' => 1])) }}"
                        data-announcement-tab
                        data-status="{{ $status }}"
                        data-mine="1"
                        class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-bold uppercase tracking-[0.14em] transition {{ $mine ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
                    >
                        {{ __('My Announcements') }}
                    </a>
                </div>
            </div>

            <form id="announcementFilterForm" method="GET" action="{{ route('announcements.index') }}" data-filter-url="{{ route('announcements.filter') }}" class="mb-4 flex flex-col gap-2 rounded-xl border border-gray-100 bg-gray-50/70 p-2 sm:flex-row sm:items-center">
                <input type="hidden" id="announcementMine" name="mine" value="{{ $mine ? 1 : 0 }}">
                <input type="hidden" id="announcementStatus" name="status" value="{{ $status }}">

                <div class="contents">
                    <div class="relative min-w-0 sm:flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input
                            id="announcementSearch"
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="{{ __('Search title, content, or type...') }}"
                            class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            data-announcement-autofilter
                        >
                    </div>

                   

                    <div class="sm:w-48 sm:shrink-0">
                        <select id="announcementMonth" name="month" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all" {{ $month === 'all' ? 'selected' : '' }}>{{ __('All Months') }}</option>
                            @foreach($months as $option)
                                <option value="{{ $option['value'] }}" {{ $month === $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:w-40 sm:shrink-0">
                        <select id="announcementSort" name="sort" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>{{ __('Latest') }}</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>{{ __('Oldest') }}</option>
                            <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>{{ __('Title A-Z') }}</option>
                        </select>
                    </div>

                    <div class="flex shrink-0 gap-2">
                        <button type="button" id="announcementFilterReset" class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-xs font-black uppercase tracking-widest text-gray-600 transition hover:bg-gray-50">
                            {{ __('Reset') }}
                        </button>
                    </div>
                </div>
            </form>

            <div id="announcementResults">
                @include('announcements.partials.results', ['announcements' => $announcements])
            </div>
        </div>

        @if($canPost)
            <div x-show="openAnnouncement" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-transition>
                <div @click.away="openAnnouncement = false" class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900">{{ __('Create Announcement') }}</h2>
                            <p class="text-sm font-medium text-gray-500">{{ __('Post an announcement for employees.') }}</p>
                        </div>

                        <button type="button" @click="openAnnouncement = false" class="text-2xl text-gray-400 transition hover:text-red-500">
                            &times;
                        </button>
                    </div>

                    <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 overflow-y-auto p-6">
                        @csrf

                        <div>
                            <label for="modalTitle" class="mb-1 block text-sm font-bold text-gray-700">{{ __('Title') }} <span class="text-red-500">*</span></label>
                            <input
                                id="modalTitle"
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="{{ __('e.g. Quarterly Team Sync Up') }}"
                                class="w-full rounded-lg border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-400 @enderror"
                                required
                            >
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="modalTags" class="mb-1 block text-sm font-bold text-gray-700">{{ __('Announcement Type') }}</label>
                            <select
                                id="modalTags"
                                name="tags"
                                class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 focus:border-indigo-500 focus:ring-indigo-500 @error('tags') border-red-400 @enderror"
                            >
                                @foreach($categories as $option)
                                    <option value="{{ $option }}" {{ old('tags', 'General') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="modalContent" class="mb-1 block text-sm font-bold text-gray-700">{{ __('Content') }} <span class="text-red-500">*</span></label>
                            <textarea
                                id="modalContent"
                                name="content"
                                rows="6"
                                placeholder="{{ __('Provide detailed information...') }}"
                                class="w-full resize-none rounded-lg border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 @error('content') border-red-400 @enderror"
                                required
                            >{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ fileName: '' }">
                            <label for="modalAttachment" class="mb-1 block text-sm font-bold text-gray-700">
                                {{ __('PDF Attachment') }}
                                <span class="text-xs font-medium text-gray-400">{{ __('Optional') }}</span>
                            </label>
                            <input
                                id="modalAttachment"
                                x-ref="modalAttachment"
                                type="file"
                                name="attachment"
                                accept=".pdf,application/pdf"
                                class="hidden"
                                @change="fileName = $event.target.files[0]?.name || ''"
                            >

                            <label for="modalAttachment" class="flex cursor-pointer items-center justify-between rounded-xl border-2 border-dashed border-gray-200 bg-gray-50/50 p-4 transition hover:border-indigo-400 @error('attachment') border-red-300 @enderror">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="rounded-lg bg-indigo-100 p-2 text-indigo-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span x-show="!fileName" class="text-sm font-bold text-gray-500">{{ __('Attach PDF File') }}</span>
                                        <span x-show="fileName" x-text="fileName" class="block max-w-sm truncate text-sm font-bold text-indigo-700"></span>
                                        <p class="text-xs text-gray-400">{{ __('PDF file only (Max 5MB)') }}</p>
                                    </div>
                                </div>

                                <span x-show="!fileName" class="rounded-full bg-indigo-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-indigo-600">
                                    {{ __('Browse') }}
                                </span>
                                <button type="button" x-show="fileName" @click.prevent="fileName = ''; $refs.modalAttachment.value = ''" class="text-red-500 hover:text-red-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </label>
                            @error('attachment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 border-t pt-4">
                            <button type="button" @click="openAnnouncement = false" class="rounded-lg border border-gray-200 px-5 py-2 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-bold text-white transition hover:bg-indigo-700">
                                {{ __('Post Announcement') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
    <script>
        const announcementFilterForm = document.getElementById('announcementFilterForm');
        const announcementSearchInput = document.querySelector('[data-announcement-autofilter]');
        const announcementCategorySelect = document.getElementById('announcementCategory');
        const announcementYearSelect = document.getElementById('announcementYear');
        const announcementMonthSelect = document.getElementById('announcementMonth');
        const announcementSortSelect = document.getElementById('announcementSort');
        const announcementResults = document.getElementById('announcementResults');
        const announcementFilterReset = document.getElementById('announcementFilterReset');
        const announcementMineInput = document.getElementById('announcementMine');
        const announcementStatusInput = document.getElementById('announcementStatus');
        const announcementTabs = document.querySelectorAll('[data-announcement-tab]');
        let announcementSearchTimer;

        function updateAnnouncementTabs(activeMine) {
            announcementTabs.forEach((tab) => {
                const isActive = tab.dataset.mine === activeMine;
                tab.classList.toggle('border-indigo-600', isActive);
                tab.classList.toggle('text-indigo-600', isActive);
                tab.classList.toggle('border-transparent', !isActive);
                tab.classList.toggle('text-gray-500', !isActive);
            });
        }

        async function filterAnnouncements(requestUrl = null, browserUrl = null) {
            if (!announcementFilterForm || !announcementResults) {
                return;
            }

            const filterUrl = announcementFilterForm.dataset.filterUrl;
            const params = new URLSearchParams(new FormData(announcementFilterForm));
            const nextRequestUrl = requestUrl || `${filterUrl}?${params.toString()}`;
            const nextBrowserUrl = browserUrl || `${announcementFilterForm.action}?${params.toString()}`;

            announcementResults.style.opacity = '0.55';

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
                announcementResults.innerHTML = data.html;
                window.history.replaceState({}, '', nextBrowserUrl);
            } catch (error) {
                announcementFilterForm.submit();
            } finally {
                announcementResults.style.opacity = '1';
            }
        }

        if (announcementFilterForm) {
            announcementFilterForm.addEventListener('submit', (event) => {
                event.preventDefault();
                filterAnnouncements();
            });
        }

        if (announcementFilterForm && announcementSearchInput) {
            announcementSearchInput.addEventListener('input', () => {
                clearTimeout(announcementSearchTimer);
                announcementSearchTimer = setTimeout(() => {
                    filterAnnouncements();
                }, 600);
            });
        }

        if (announcementCategorySelect) {
            announcementCategorySelect.addEventListener('change', () => filterAnnouncements());
        }

        if (announcementYearSelect) {
            announcementYearSelect.addEventListener('change', () => filterAnnouncements());
        }

        if (announcementMonthSelect) {
            announcementMonthSelect.addEventListener('change', () => filterAnnouncements());
        }

        if (announcementSortSelect) {
            announcementSortSelect.addEventListener('change', () => filterAnnouncements());
        }

        if (announcementFilterReset) {
            announcementFilterReset.addEventListener('click', () => {
                if (announcementSearchInput) {
                    announcementSearchInput.value = '';
                }

                if (announcementCategorySelect) {
                    announcementCategorySelect.value = 'all';
                }

                if (announcementYearSelect) {
                    announcementYearSelect.value = '{{ now()->year }}';
                }

                if (announcementMonthSelect) {
                    announcementMonthSelect.value = 'all';
                }

                if (announcementSortSelect) {
                    announcementSortSelect.value = 'latest';
                }

                filterAnnouncements();
            });
        }

        announcementTabs.forEach((tab) => {
            tab.addEventListener('click', (event) => {
                event.preventDefault();

                if (announcementMineInput) {
                    announcementMineInput.value = tab.dataset.mine || '0';
                }

                if (announcementStatusInput) {
                    announcementStatusInput.value = tab.dataset.status || 'all';
                }

                updateAnnouncementTabs(tab.dataset.mine || '0');
                filterAnnouncements();
            });
        });

        if (announcementResults) {
            announcementResults.addEventListener('click', (event) => {
                const link = event.target.closest('a');

                if (!link || !link.href || !link.closest('nav')) {
                    return;
                }

                event.preventDefault();

                const url = new URL(link.href);
                const filterUrl = announcementFilterForm.dataset.filterUrl;
                filterAnnouncements(`${filterUrl}${url.search}`, `${announcementFilterForm.action}${url.search}`);
            });
        }
    </script>
</x-app-layout>
