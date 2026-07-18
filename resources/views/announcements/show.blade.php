<x-app-layout>
    <x-slot name="title">{{ __('Announcement Details') }}</x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5">
                <a href="{{ (auth()->user()->role === 'admin' || auth()->user()->role === 'hrstaff') ? route('announcements.index') : route('announcements.view') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
            <article class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8 md:p-12">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <span class="text-xs font-medium text-gray-400 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $announcement->created_at->format('M d, Y h:i A') }}
                        </span>

                        @if($announcement->tags)
                            @foreach(explode(',', $announcement->tags) as $tag)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ trim($tag) }}
                                </span>
                            @endforeach
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-8">
                        {{ $announcement->title }}
                    </h1>

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hrstaff')
                        <div class="mb-10 flex justify-end gap-2">
                                <a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex h-9 w-9 items-center justify-center bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.651-1.651a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 7.125L16.875 4.5" />
                                    </svg>
                                    <span class="sr-only">{{ __('Edit') }}</span>
                                </a>
                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this announcement?') }}');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-9 w-9 items-center justify-center bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors" title="{{ __('Delete') }}" aria-label="{{ __('Delete') }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-9 0V5.25A1.25 1.25 0 0110.25 4h3.5A1.25 1.25 0 0115 5.25V7m-7 0l.75 12A2 2 0 0010.75 21h2.5a2 2 0 002-1.875L16 7" />
                                        </svg>
                                        <span class="sr-only">{{ __('Delete') }}</span>
                                    </button>
                                </form>
                        </div>
                    @endif

                    <div class="prose prose-indigo max-w-none text-gray-700 leading-relaxed text-lg whitespace-pre-wrap">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>

                    @if($announcement->attachment_path)
                        <div class="mt-10 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-3">{{ __('Attachment') }}</span>
                            <a href="{{ route('announcements.attachment', $announcement) }}" target="_blank" class="inline-flex items-center gap-3 rounded-xl bg-white px-4 py-3 text-sm font-bold text-indigo-700 shadow-sm hover:bg-indigo-50 hover:text-indigo-900 transition-colors">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <span class="truncate">{{ basename($announcement->attachment_path) }}</span>
                            </a>
                        </div>
                    @endif

                    <div class="mt-12 pt-8 border-t border-gray-100 text-sm text-gray-400">
                        <p>
                            {{ $announcement->author?->display_name ?: $announcement->author?->name ?: __('HR Office') }}
                            <span class="mx-1 text-gray-300">|</span>
                            {{ $announcement->author?->employee?->position ?: $announcement->author?->role ?: __('Staff') }}
                        </p>
                    </div>
                </div>
            </article>

        </div>
    </div>
</x-app-layout>
