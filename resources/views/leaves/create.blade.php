<x-app-layout>
    <x-slot name="title">{{ __('File New Leave Request') }}</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 ring-1 ring-black/5">
                <div class="p-8">
                    <form method="POST" action="{{ route('leaves.store') }}" class="space-y-8">
                        @csrf

                        <!-- Leave Type -->
                        @php
                            $leaveGroups = [
                                'Regular Leave' => ['Vacation Leave', 'Mandatory/Force Leave', 'Sick Leave'],
                                'Parental / Family Leave' => ['Maternity Leave', 'Paternity Leave', 'Solo Parent Leave', 'Adoption Leave'],
                                'Special Privilege / Study Leave' => ['Special Privilege Leave', 'Study Leave'],
                                'Women\'s / Gender-Related Leave' => ['VAWC Leave', 'Special Leave Benefits for Women'],
                                'Emergency / Rehabilitation Leave' => ['Rehabilitation Leave', 'Special Emergency (Calamity) Leave'],
                            ];
                        @endphp
                        <div x-data="{
                            open: false,
                            selectedId: '{{ old('leave_type_id', '') }}',
                            selectedName: '',
                            types: {{ Js::from($leaveTypes->keyBy('id')->map(fn($t) => ['name' => $t->name, 'description' => $t->description, 'legal_basis' => $t->legal_basis])) }},
                            get info() { return this.types[this.selectedId] || null; },
                            pick(id, name) { this.selectedId = id; this.selectedName = name; this.open = false; },
                            init() { if (this.selectedId && this.types[this.selectedId]) this.selectedName = this.types[this.selectedId].name; }
                        }" @click.outside="open = false" class="relative">
                            <x-input-label for="leave_type_id" :value="__('Type of Leave')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                            <input type="hidden" name="leave_type_id" :value="selectedId">

                            <!-- Trigger Button -->
                            <button type="button" @click="open = !open" class="mt-1 relative w-full text-left text-sm font-bold border border-gray-200 rounded-xl py-3 px-4 bg-gray-50/50 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" :class="open && 'border-indigo-500 ring-2 ring-indigo-500'">
                                <span x-show="!selectedName" class="text-gray-400">{{ __('Select a leave type') }}</span>
                                <span x-show="selectedName" x-text="selectedName" class="text-gray-900"></span>
                                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <x-input-error class="mt-2" :messages="$errors->get('leave_type_id')" />

                            <!-- Dropdown List -->
                            <div x-show="open" x-transition.opacity.duration.150ms x-cloak class="absolute z-50 mt-1 w-full bg-white rounded-xl shadow-lg border border-gray-200 max-h-[400px] overflow-y-auto">
                                @foreach($leaveGroups as $group => $names)
                                    @php $groupTypes = $leaveTypes->filter(fn($t) => in_array($t->name, $names)); @endphp
                                    @if($groupTypes->isNotEmpty())
                                        {{-- Section Header --}}
                                        <div class="sticky top-0 bg-gray-50 px-4 py-2 border-b border-gray-200">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $group }}</span>
                                        </div>
                                        {{-- Items --}}
                                        @foreach($groupTypes as $type)
                                            @php $balance = $credits[$type->id]->balance ?? 0; @endphp
                                            <button type="button"
                                                @click="pick('{{ $type->id }}', '{{ $type->name }}')"
                                                class="w-full text-left px-4 py-3 border-b border-gray-100 hover:bg-indigo-50 transition-colors"
                                                :class="selectedId == '{{ $type->id }}' && 'bg-indigo-50'">
                                                <div class="flex items-center justify-between">
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-bold text-gray-800" :class="selectedId == '{{ $type->id }}' && 'text-indigo-700'">
                                                            {{ $type->name }}
                                                            <span x-show="selectedId == '{{ $type->id }}'" class="text-indigo-600 ml-1">✓</span>
                                                        </div>
                                                        @if($type->legal_basis)
                                                            <div class="text-[11px] text-gray-400 italic mt-0.5">{{ $type->legal_basis }}</div>
                                                        @endif
                                                    </div>
                                                    <span class="flex-shrink-0 ml-3 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $balance > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                                        {{ number_format($balance, 1) }} days
                                                    </span>
                                                </div>
                                            </button>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>

                            <!-- Info Panel -->
                            <div x-show="info" x-transition x-cloak class="mt-3 p-4 bg-indigo-50/70 border border-indigo-100 rounded-xl">
                                <p class="text-sm text-gray-700 leading-relaxed" x-text="info?.description"></p>
                                <p class="mt-2 text-xs text-indigo-600/80 italic" x-show="info?.legal_basis">
                                    <span class="font-black uppercase tracking-wider text-[10px] text-indigo-500 not-italic">{{ __('Legal Basis') }}:</span>
                                    <span x-text="info?.legal_basis"></span>
                                </p>
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
