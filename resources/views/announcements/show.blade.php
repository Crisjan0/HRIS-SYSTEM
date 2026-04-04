<x-app-layout>
    <x-slot name="title">{{ __('Announcement Details') }}</x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8 md:p-12">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        @if($announcement->is_published)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 uppercase tracking-wider">
                                {{ __('Published') }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 uppercase tracking-wider">
                                {{ __('Draft') }}
                            </span>
                        @endif

                        <span class="text-xs font-medium text-gray-400 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $announcement->created_at->format('M d, Y h:i A') }}
                        </span>

                        @if($announcement->tags)
                            @foreach(explode(',', $announcement->tags) as $tag)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    #{{ trim($tag) }}
                                </span>
                            @endforeach
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-8">
                        {{ $announcement->title }}
                    </h1>

                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl mb-10">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md ring-4 ring-white">
                            {{ substr($announcement->author->display_name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-gray-900">{{ $announcement->author->display_name }}</p>
                            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $announcement->author->role ?? __('Staff') }}</p>
                        </div>

                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hrstaff')
                            <div class="ml-auto flex items-center gap-2">
                                <a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg hover:bg-indigo-100 transition-colors uppercase tracking-widest">
                                    {{ __('Edit') }}
                                </a>
                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this announcement?') }}');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 text-xs font-bold rounded-lg hover:bg-red-100 transition-colors uppercase tracking-widest">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="prose prose-indigo max-w-none text-gray-700 leading-relaxed text-lg whitespace-pre-wrap">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>

                    <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between text-sm text-gray-400">
                        <p>{{ __('Last updated') }}: {{ $announcement->updated_at->diffForHumans() }}</p>
                        <button onclick="window.print()" class="flex items-center hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            {{ __('Print') }}
                        </button>
                    </div>
                </div>
            </article>

            <div class="mt-8 flex justify-center">
                <a href="{{ (auth()->user()->role === 'admin' || auth()->user()->role === 'hrstaff') ? route('announcements.index') : route('announcements.view') }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    {{ __('Back to Board') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
