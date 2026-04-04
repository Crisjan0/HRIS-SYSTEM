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
                                            #{{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex items-center justify-between group-hover:bg-white transition-colors duration-300">
                            <div class="flex items-center">
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-[10px] text-white font-black shadow-md ring-2 ring-white">
                                        {{ substr($announcement->author->display_name, 0, 1) }}
                                    </div>
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full shadow-sm"></div>
                                </div>
                                <div class="ml-3 font-bold">
                                    <p class="text-xs text-gray-900 leading-none mb-0.5">{{ $announcement->author->display_name }}</p>
                                    <p class="text-[9px] text-indigo-500 uppercase tracking-widest">{{ $announcement->author->role ?? __('Staff') }}</p>
                                </div>
                            </div>
                            
                            <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center text-xs font-black text-indigo-600 hover:text-indigo-700 uppercase tracking-widest gap-1 group/btn">
                                {{ __('Read More') }}
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
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
                            <p class="text-gray-500 font-medium leading-relaxed">{{ __('There are no official announcements at the moment. Check back later for updates.') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>

    <style>
        .shadow-special {
            box-shadow: 0 20px 50px -12px rgba(99, 102, 241, 0.1);
        }
    </style>
</x-app-layout>
