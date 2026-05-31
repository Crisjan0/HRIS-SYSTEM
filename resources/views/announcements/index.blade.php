<x-app-layout>
    <x-slot name="title">{{ __('Manage Announcements') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-r-lg shadow-sm flex items-center justify-between" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($announcements as $announcement)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-center justify-between mb-4">
                                @if($announcement->is_published)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
                                        {{ __('Published') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                        {{ __('Draft') }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $announcement->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <a href="{{ route('announcements.show', $announcement) }}" class="block group/title">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 group-hover/title:text-indigo-600 transition-colors cursor-pointer hover:underline decoration-indigo-200 underline-offset-4">
                                    {{ $announcement->title }}
                                </h3>
                            </a>

                            <div class="text-sm text-gray-600 line-clamp-3 mb-4">
                                {{ Str::limit(strip_tags($announcement->content), 150) }}
                            </div>

                            @if($announcement->tags)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(explode(',', $announcement->tags) as $tag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">
                                            #{{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center">
                                <x-profile-avatar :user="$announcement->author" size="xs" variant="indigo" class="ring-2 ring-white shadow-sm" />
                                <span class="ml-2 text-xs font-semibold text-gray-700">{{ $announcement->author->display_name }}</span>
                            </div>
                            
                            <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center text-xs font-black text-indigo-600 hover:text-indigo-700 uppercase tracking-widest gap-1 group/btn">
                                {{ __('View') }}
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ __('No announcements yet') }}</h3>
                            <p class="text-gray-500 mb-6">{{ __('Create your first announcement to share important updates.') }}</p>
                            <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-150">
                                {{ __('Get Started') }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
