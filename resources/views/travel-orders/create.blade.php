<x-app-layout>
    <x-slot name="title">{{ __('Create Travel Order') }}</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-gray-100">
                <h2 class="text-2xl font-black text-gray-900 mb-8">{{ __('Create Travel Order') }}</h2>

                <form action="{{ route('travel-orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Travel Type --}}
                    <div>
                        <label for="travel_type" class="block text-sm font-bold text-gray-700 mb-1">{{ __('Travel Type') }} <span class="text-red-500">*</span></label>
                        <select id="travel_type" name="travel_type" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="">-- Select Travel Type --</option>
                            <option value="local" {{ old('travel_type') === 'local' ? 'selected' : '' }}>Local</option>
                            <option value="foreign" {{ old('travel_type') === 'foreign' ? 'selected' : '' }}>Foreign</option>
                            <option value="official_business" {{ old('travel_type') === 'official_business' ? 'selected' : '' }}>Official Business</option>
                        </select>
                        @error('travel_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Travel Dates --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="travel_date_start" class="block text-sm font-bold text-gray-700 mb-1">{{ __('Travel Date From') }} <span class="text-red-500">*</span></label>
                            <input type="date" id="travel_date_start" name="travel_date_start" value="{{ old('travel_date_start') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            @error('travel_date_start') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="travel_date_end" class="block text-sm font-bold text-gray-700 mb-1">{{ __('Travel Date To') }} <span class="text-red-500">*</span></label>
                            <input type="date" id="travel_date_end" name="travel_date_end" value="{{ old('travel_date_end') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            @error('travel_date_end') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Employee(s) (Optional) --}}
                    <div>
                        <label for="companions" class="block text-sm font-bold text-gray-700 mb-1">
                            {{ __('Employee(s)') }}
                            <span class="text-gray-400 font-normal text-xs">— Optional</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-2">{{ __('Select employees who will travel with you.') }}</p>
                        <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-xl p-3 space-y-2 bg-gray-50/50">
                            @foreach($employees as $emp)
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition-colors cursor-pointer">
                                    <input type="checkbox" name="companions[]" value="{{ $emp->id }}"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        {{ in_array($emp->id, old('companions', [])) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700 font-medium">{{ $emp->lastname }}, {{ $emp->firstname }} {{ $emp->middlename }}</span>
                                    @if($emp->position)
                                        <span class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $emp->position }}</span>
                                    @endif
                                </label>
                            @endforeach

                            @if($employees->isEmpty())
                                <p class="text-xs text-gray-400 italic p-2">No other employees found.</p>
                            @endif
                        </div>
                        @error('companions') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        @error('companions.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Place(s) of Travel --}}
                    <div>
                        <label for="places_of_travel" class="block text-sm font-bold text-gray-700 mb-1">{{ __('Place(s) of Travel') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="places_of_travel" name="places_of_travel" value="{{ old('places_of_travel') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. Manila, Cebu City" required>
                        @error('places_of_travel') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Purpose of Travel --}}
                    <div>
                        <label for="purpose" class="block text-sm font-bold text-gray-700 mb-1">{{ __('Purpose of Travel') }} <span class="text-red-500">*</span></label>
                        <textarea id="purpose" name="purpose" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Describe the purpose of travel..." required>{{ old('purpose') }}</textarea>
                        @error('purpose') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Attach File --}}
                    <div x-data="{ fileName: '' }">
                        <label for="attachment" class="block text-sm font-bold text-gray-700 mb-1">
                            {{ __('Attach File') }}
                            <span class="text-gray-400 font-normal text-xs">— Optional</span>
                        </label>
                        <div class="relative group">
                            <input type="file"
                                name="attachment"
                                id="attachment"
                                x-ref="attachment"
                                class="hidden"
                                @change="fileName = $event.target.files[0]?.name || ''"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">

                            <label for="attachment"
                                class="flex items-center justify-between w-full border-2 border-dashed border-gray-200 group-hover:border-indigo-400 rounded-xl p-4 bg-gray-50/50 cursor-pointer transition-all duration-300">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span x-show="!fileName" class="text-sm font-bold text-gray-500">{{ __('Attach Document / File') }}</span>
                                        <span x-show="fileName" x-text="fileName" class="text-sm font-bold text-indigo-700 truncate max-w-xs"></span>
                                        <p class="text-xs text-gray-400">{{ __('PDF, JPG, PNG or DOC (Max 5MB)') }}</p>
                                    </div>
                                </div>

                                <span x-show="!fileName" class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    BROWSE
                                </span>
                                <button type="button" x-show="fileName" @click.prevent="fileName = ''; $refs.attachment.value = ''" class="text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </label>
                        </div>
                        @error('attachment') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('travel-orders.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200 hover:-translate-y-0.5">
                            {{ __('Submit Travel Order') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
