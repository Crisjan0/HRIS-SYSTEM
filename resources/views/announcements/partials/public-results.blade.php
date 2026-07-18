<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($announcements as $announcement)
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 flex flex-col group h-full">
            <div class="p-8 flex-1">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : $announcement->created_at->format('M d, Y') }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $announcement->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('announcements.show', $announcement) }}" class="block group/title">
                    <h3 class="text-xl font-black text-gray-900 mb-3 group-hover/title:text-indigo-600 transition-colors line-clamp-2 leading-tight tracking-tight">
                        {{ $announcement->title }}
                    </h3>
                </a>

                <div class="text-sm text-gray-600 line-clamp-4 leading-relaxed font-medium mb-6">
                    {{ Str::limit(strip_tags($announcement->content), 180) }}
                </div>

                @if($announcement->tags)
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach(explode(',', $announcement->tags) as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-[10px] font-black bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-100 uppercase tracking-wider">
                                {{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex items-center justify-between group-hover:bg-white transition-colors duration-300">
                <div class="min-w-0 font-bold">
                    <p class="truncate text-xs text-gray-900 leading-none mb-1">
                        {{ $announcement->author?->display_name ?: $announcement->author?->name ?: __('HR Office') }}
                    </p>
                    <p class="truncate text-[9px] text-gray-500 uppercase tracking-widest">
                        {{ $announcement->author?->employee?->position ?: $announcement->author?->role ?: __('Staff') }}
                        @if($announcement->author?->employee?->division)
                            <span class="mx-1 text-gray-300">|</span>{{ $announcement->author->employee->division }}
                        @endif
                    </p>
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    @if($announcement->attachment_path)
                        <a href="{{ route('announcements.attachment', $announcement) }}" target="_blank" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-700 transition hover:bg-indigo-100" title="{{ __('PDF Attachment') }}" aria-label="{{ __('PDF Attachment') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </a>
                    @endif
                    <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center gap-1 text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-700 group/btn">
                        {{ __('Read More') }}
                        <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-[2.5rem] shadow-special border border-gray-100 p-20 text-center">
            <div class="flex flex-col items-center max-w-sm mx-auto">
                <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6 animate-bounce">
                    <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 italic uppercase tracking-tight">{{ __('All caught up!') }}</h3>
                <p class="text-gray-500 font-medium leading-relaxed">{{ __('There are no official announcements matching the current filters.') }}</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-12">
    {{ $announcements->links() }}
</div>
