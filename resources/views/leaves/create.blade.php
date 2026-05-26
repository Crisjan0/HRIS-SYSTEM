<x-app-layout>
    <x-slot name="title">{{ __('File New Leave Request') }}</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 ring-1 ring-black/5">
                <div class="p-8">
                    <form method="POST" action="{{ route('leaves.store') }}" class="space-y-8">
                        @csrf

                        <!-- Leave Type -->
                        <div x-data="{
                            selectedType: '{{ old('leave_type_id', '') }}',
                            leaveTypes: {{ Js::from($leaveTypes->keyBy('id')->map(fn($t) => ['description' => $t->description, 'legal_basis' => $t->legal_basis])) }},
                            get info() { return this.leaveTypes[this.selectedType] || null; }
                        }">
                            <x-input-label for="leave_type_id" :value="__('Type of Leave')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                            <select id="leave_type_id" name="leave_type_id" x-model="selectedType" class="mt-1 block w-full text-sm font-bold border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm py-3 px-4 bg-gray-50/50" required>
                                <option value="" disabled>{{ __('Select a leave type') }}</option>
                                @foreach($leaveTypes as $type)
                                    @php
                                        $balance = $credits[$type->id]->balance ?? 0;
                                    @endphp
                                    <option value="{{ $type->id }}" @selected(old('leave_type_id') == $type->id)>
                                        {{ $type->name }} — {{ $type->legal_basis }} ({{ number_format($balance, 1) }} {{ __('days available') }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('leave_type_id')" />

                            <!-- Leave Type Info Panel -->
                            <div x-show="info" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="mt-3 p-4 bg-indigo-50/70 border border-indigo-100 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-700 leading-relaxed" x-text="info?.description"></p>
                                        <p class="mt-2 text-xs font-semibold text-indigo-600/80 italic" x-show="info?.legal_basis">
                                            <span class="font-black uppercase tracking-wider text-[10px] text-indigo-500 not-italic">{{ __('Legal Basis') }}:</span>
                                            <span x-text="info?.legal_basis"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="start_date" :value="__('Start Date')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full text-sm font-bold border-gray-100 py-3 px-4 bg-gray-50/50" :value="old('start_date')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('End Date')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full text-sm font-bold border-gray-100 py-3 px-4 bg-gray-50/50" :value="old('end_date')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                            </div>
                        </div>

                        <!-- Reason -->
                        <div>
                            <x-input-label for="reason" :value="__('Reason for Leave')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                            <textarea id="reason" name="reason" class="mt-1 block w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3 px-4 bg-gray-50/50" rows="5" required placeholder="{{ __('Please provide a detailed reason for your leave request...') }}">{{ old('reason') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                        </div>

                        <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-50">
                            <a href="{{ route('leaves.index') }}" class="text-sm font-black text-gray-400 hover:text-indigo-600 transition-colors duration-300 uppercase tracking-widest">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 border-none shadow-xl shadow-indigo-200/50 px-8 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-1">
                                {{ __('Submit Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
