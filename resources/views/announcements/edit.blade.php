<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('announcements.index') }}" class="inline-flex items-center p-2 text-gray-500 hover:text-indigo-600 transition-colors bg-white rounded-full shadow-sm hover:shadow transition-all group">
                <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Announcement') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('announcements.update', $announcement) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Title') }}</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" placeholder="{{ __('What\'s the news?') }}" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all text-gray-900 font-medium placeholder:text-gray-400 @error('title') border-red-500 @enderror" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tags" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Tags (Comma separated)') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-semibold">#</span>
                                </div>
                                <input type="text" name="tags" id="tags" value="{{ old('tags', $announcement->tags) }}" placeholder="HR, Event, Important" class="w-full pl-8 pr-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all text-sm @error('tags') border-red-500 @enderror">
                            </div>
                            @error('tags')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Content') }}</label>
                            <textarea name="content" id="content" rows="12" placeholder="{{ __('Write your announcement detail here...') }}" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all text-gray-900 @error('content') border-red-500 @enderror" required>{{ old('content', $announcement->content) }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 text-xs text-gray-400 flex items-center justify-between">
                                <span>{{ __('Formatting with plain text or HTML is supported.') }}</span>
                                <span id="charCount">{{ strlen($announcement->content) }} {{ __('characters') }}</span>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="is_published" id="is_published" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition-all cursor-pointer" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_published" class="font-bold text-gray-900 cursor-pointer">{{ __('Published Status') }}</label>
                                    <p class="text-gray-500">{{ __('Announcement will be visible to everyone if checked.') }}</p>
                                </div>
                            </div>
                            
                            <div class="hidden sm:block">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-amber-100 text-amber-800" id="draftBadge" style="{{ $announcement->is_published ? 'display: none' : '' }}">
                                    {{ __('Status: Draft') }}
                                </span>
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-green-100 text-green-800" id="publishedBadge" style="{{ !$announcement->is_published ? 'display: none' : '' }}">
                                    {{ __('Status: Published') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('announcements.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-700 hover:text-gray-900 transition-colors uppercase tracking-wider">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-sm hover:shadow-indigo-200 transition-all uppercase tracking-wider text-sm">
                                {{ __('Update Announcement') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const contentArea = document.getElementById('content');
        const charCount = document.getElementById('charCount');
        const publishCheck = document.getElementById('is_published');
        const draftBadge = document.getElementById('draftBadge');
        const publishedBadge = document.getElementById('publishedBadge');

        if(contentArea) {
            contentArea.addEventListener('input', () => {
                const count = contentArea.value.length;
                charCount.textContent = `${count} characters`;
            });
        }

        if(publishCheck) {
            publishCheck.addEventListener('change', () => {
                if(publishCheck.checked) {
                    draftBadge.style.display = 'none';
                    publishedBadge.style.display = 'inline-flex';
                } else {
                    draftBadge.style.display = 'inline-flex';
                    publishedBadge.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
