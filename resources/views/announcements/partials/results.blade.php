<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
    @forelse($announcements as $announcement)
        @php
            $tags = collect(explode(',', $announcement->tags ?? 'General'))->map(fn ($tag) => trim($tag))->filter();
            $primaryTag = $tags->first() ?: 'General';
        @endphp

        <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-xl">
            <div class="flex-1 p-6">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-600">
                            {{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : $announcement->created_at->format('M d, Y') }}
                        </p>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                            {{ $announcement->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="shrink-0 rounded-xl border border-gray-100 bg-gray-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-gray-500">
                        {{ $primaryTag }}
                    </span>
                </div>

                <a href="{{ route('announcements.show', $announcement) }}" class="group">
                    <h2 class="line-clamp-2 text-xl font-black leading-tight text-gray-900 transition group-hover:text-indigo-600">
                        {{ $announcement->title }}
                    </h2>
                </a>

                <p class="mt-3 line-clamp-4 text-sm font-medium leading-6 text-gray-600">
                    {{ Str::limit(strip_tags($announcement->content), 180) }}
                </p>
            </div>

            <div class="flex items-center justify-between gap-4 border-t border-gray-50 bg-gray-50/70 px-6 py-4">
                <div class="min-w-0 font-bold">
                    <p class="truncate text-xs leading-none text-gray-900">
                        {{ $announcement->author?->display_name ?: $announcement->author?->name ?: __('HR Office') }}
                    </p>
                    <p class="mt-1 truncate text-[9px] uppercase tracking-widest text-gray-500">
                        {{ $announcement->author?->employee?->position ?: $announcement->author?->role ?: __('Staff') }}
                        @if($announcement->author?->employee?->division)
                            <span class="mx-1 text-gray-300">|</span>{{ $announcement->author->employee->division }}
                        @endif
                    </p>
                </div>

                <div class="flex shrink-0 items-center gap-1.5">
                    @if($announcement->attachment_path)
                        <a href="{{ route('announcements.attachment', $announcement) }}" target="_blank" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-700 transition hover:bg-indigo-100" title="{{ __('PDF Attachment') }}" aria-label="{{ __('PDF Attachment') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </a>
                    @endif
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
        <div class="col-span-full rounded-2xl border border-dashed border-gray-200 bg-white p-12 text-center">
            <h3 class="text-lg font-black text-gray-900">{{ __('No announcements found') }}</h3>
            <p class="mt-1 text-sm font-medium text-gray-500">{{ __('No announcements match the current search or filter.') }}</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $announcements->links() }}
</div>
