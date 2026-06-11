<x-app-layout>
    <x-slot name="title">{{ __('Manage Announcements') }}</x-slot>

    @php
        $role = strtolower(auth()->user()->role ?? '');
        $canPost = in_array($role, ['admin', 'hrstaff', 'regionaldirector', 'regional director']);
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

            <div class="flex justify-end">
                @if($canPost)
                    <button
                        type="button"
                        @click="openAnnouncement = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-indigo-100 transition hover:bg-indigo-700"
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
                        href="{{ route('announcements.index', array_filter(['search' => $search, 'category' => $category !== 'all' ? $category : null, 'status' => 'all'])) }}"
                        class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-bold uppercase tracking-[0.14em] transition {{ $status === 'all' && !$mine ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
                    >
                        {{ __('All Announcements') }}
                    </a>
                    <a
                        href="{{ route('announcements.index', array_filter(['search' => $search, 'category' => $category !== 'all' ? $category : null, 'status' => $status, 'mine' => 1])) }}"
                        class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-bold uppercase tracking-[0.14em] transition {{ $mine ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
                    >
                        {{ __('My Announcements') }}
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('announcements.index') }}" class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
                @if($mine)
                    <input type="hidden" name="mine" value="1">
                @endif
                @if($status !== 'all')
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif

                <div class="flex flex-col gap-3 md:flex-row md:items-center">
                    <div class="min-w-0 flex-1">
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                id="announcementSearch"
                                type="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="{{ __('Search title, content, or type...') }}"
                                class="w-full rounded-lg border-gray-200 py-3 pl-11 pr-4 text-sm font-semibold text-gray-700 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>
                    </div>

                    <div class="md:w-56">
                        <select id="announcementCategory" name="category" class="w-full rounded-lg border-gray-200 bg-white py-3 text-sm font-semibold text-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all" {{ $category === 'all' ? 'selected' : '' }}>{{ __('All Types') }}</option>
                            @foreach($categories as $option)
                                <option value="{{ $option }}" {{ $category === $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex shrink-0 gap-2">
                        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('announcements.index') }}" class="rounded-lg border border-gray-200 px-5 py-3 text-xs font-black uppercase tracking-widest text-gray-600 transition hover:bg-gray-50">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </div>
            </form>

            <div class="space-y-4">
                @forelse($announcements as $announcement)
                    @php
                        $tags = collect(explode(',', $announcement->tags ?? 'General'))->map(fn ($tag) => trim($tag))->filter();
                        $primaryTag = $tags->first() ?: 'General';
                    @endphp

                    <article class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition hover:shadow-md">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="mb-3 flex flex-wrap items-center gap-2">
                                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $categoryColor($primaryTag) }}">
                                        {{ $primaryTag }}
                                    </span>

                                    @if($announcement->author_id === auth()->id())
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">{{ __('My Announcement') }}</span>
                                    @endif

                                    <span class="text-xs font-semibold text-gray-400">{{ $announcement->created_at->format('M d, Y') }}</span>
                                </div>

                                <a href="{{ route('announcements.show', $announcement) }}" class="group">
                                    <h2 class="text-xl font-black text-gray-900 transition group-hover:text-indigo-600">
                                        {{ $announcement->title }}
                                    </h2>
                                </a>

                                <p class="mt-2 max-w-4xl text-sm leading-6 text-gray-600">
                                    {{ Str::limit(strip_tags($announcement->content), 220) }}
                                </p>

                                <div class="mt-4 flex flex-wrap items-center gap-3 text-xs font-semibold text-gray-400">
                                    <span>
                                        {{ __('Posted by') }}
                                        <span class="text-gray-600">
                                            {{ $announcement->author?->display_name ?: $announcement->author?->name ?: $announcement->author?->email ?: __('HR Office') }}
                                        </span>
                                        @if($announcement->author?->role)
                                            <span class="uppercase tracking-widest text-indigo-500">
                                                ({{ $announcement->author->role }})
                                            </span>
                                        @endif
                                    </span>

                                    @if($announcement->attachment_path)
                                        <a href="{{ asset('storage/' . $announcement->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-3 py-1 text-indigo-700 transition hover:bg-indigo-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            {{ __('PDF Attachment') }}
                                        </a>
                                    @else
                                        <span>{{ __('No attachment') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-700 transition hover:bg-indigo-100" title="{{ __('View') }}" aria-label="{{ __('View') }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>

                                <a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition hover:bg-blue-100" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.651-1.651a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 7.125L16.875 4.5" />
                                    </svg>
                                </a>

                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('{{ __('Delete this announcement?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-700 transition hover:bg-red-100" title="{{ __('Delete') }}" aria-label="{{ __('Delete') }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-9 0V5.25A1.25 1.25 0 0110.25 4h3.5A1.25 1.25 0 0115 5.25V7m-7 0l.75 12A2 2 0 0010.75 21h2.5a2 2 0 002-1.875L16 7" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-200 bg-white p-12 text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                            <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-gray-900">{{ __('No announcements found') }}</h3>
                        <p class="mt-1 text-sm font-medium text-gray-500">{{ __('No announcements match the current search or filter.') }}</p>
                    </div>
                @endforelse
            </div>

            <div>
                {{ $announcements->links() }}
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
</x-app-layout>
