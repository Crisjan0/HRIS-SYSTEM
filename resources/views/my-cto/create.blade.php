<x-app-layout>
    <x-slot name="title">{{ __('File CTO Request') }}</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-gray-100">
                <h2 class="text-2xl font-black text-gray-900 mb-2">{{ __('File CTO Request') }}</h2>
                <p class="text-sm text-gray-500 mb-8">Available balance: <span class="font-bold text-indigo-600">{{ number_format($employee->cto_balance, 1) }} hour(s)</span></p>

                <form
                    action="{{ route('my-cto.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-6"
                    x-data="ctoRequestForm('{{ old('type', 'earn') }}', '{{ old('date_start') }}', '{{ old('date_end') }}', '{{ now()->toDateString() }}')"
                    x-init="init()"
                >
                    @csrf

                    {{-- Request Type --}}
                    <div>
                        <label for="type" class="block text-sm font-bold text-gray-700 mb-1">{{ __('Request Type') }} <span class="text-red-500">*</span></label>
                        <select id="type" name="type" x-model="type" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="earn">Earn CTO - Overtime / Extra Work</option>
                            <option value="use">Use CTO - Apply Time Off</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-weekday-date-picker field="date_start" :label="__('Date From')" model="dateStart" />
                        <x-weekday-date-picker field="date_end" :label="__('Date To')" model="dateEnd" />
                    </div>

                    {{-- Hours --}}
                    <div>
                        <label for="hours" class="block text-sm font-bold text-gray-700 mb-1">
                            <span x-show="type === 'earn'">Hours Rendered</span>
                            <span x-show="type === 'use'">Hours to Use</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="hours" name="hours" value="{{ old('hours') }}" step="0.5" min="0.5" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. 8" required>
                        @error('hours') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Purpose --}}
                    <div>
                        <label for="purpose" class="block text-sm font-bold text-gray-700 mb-1">
                            <span x-show="type === 'earn'">Description of Work Rendered</span>
                            <span x-show="type === 'use'">Purpose / Reason</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea id="purpose" name="purpose" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Provide details..." required>{{ old('purpose') }}</textarea>
                        @error('purpose') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Attach File --}}
                    <div x-data="{ fileName: '' }">
                        <label for="attachment" class="block text-sm font-bold text-gray-700 mb-1">
                            {{ __('Attach File') }}
                            <span class="text-gray-400 font-normal text-xs">- Optional</span>
                        </label>
                        <div class="relative group">
                            <input type="file"
                                name="attachment"
                                id="attachment"
                                x-ref="attachment"
                                class="hidden"
                                @change="fileName = $event.target.files[0]?.name || ''"
                                accept=".pdf,application/pdf">

                            <label for="attachment"
                                class="flex items-center justify-between w-full border-2 border-dashed border-gray-200 group-hover:border-indigo-400 rounded-xl p-4 bg-gray-50/50 cursor-pointer transition-all duration-300">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span x-show="!fileName" class="text-sm font-bold text-gray-500">{{ __('Attach PDF File') }}</span>
                                        <span x-show="fileName" x-text="fileName" class="text-sm font-bold text-indigo-700 truncate max-w-xs"></span>
                                        <p class="text-xs text-gray-400">{{ __('PDF file only (Max 5MB)') }}</p>
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

                    {{-- Applicant Signature --}}
                    <div x-data="{ signatureName: '' }">
                        <label for="applicant_signature" class="block text-sm font-bold text-gray-700 mb-1">
                            {{ __('Applicant Signature') }}
                            <span class="text-gray-400 font-normal text-xs">- Optional</span>
                        </label>
                        <div class="relative group">
                            <input type="file"
                                name="applicant_signature"
                                id="applicant_signature"
                                x-ref="applicantSignature"
                                class="hidden"
                                @change="signatureName = $event.target.files[0]?.name || ''"
                                accept=".jpg,.jpeg,.png,image/jpeg,image/png">

                            <label for="applicant_signature"
                                class="flex items-center justify-between w-full border-2 border-dashed border-gray-200 group-hover:border-indigo-400 rounded-xl p-4 bg-gray-50/50 cursor-pointer transition-all duration-300">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.1 2.1 0 012.97 2.97L8.75 17.54 4.8 18.6l1.06-3.95L16.862 3.487z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6.85l2.97 2.97" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span x-show="!signatureName" class="text-sm font-bold text-gray-500">{{ __('Upload Signature Image') }}</span>
                                        <span x-show="signatureName" x-text="signatureName" class="text-sm font-bold text-indigo-700 truncate max-w-xs"></span>
                                        <p class="text-xs text-gray-400">{{ __('JPG or PNG only (Max 2MB)') }}</p>
                                    </div>
                                </div>

                                <span x-show="!signatureName" class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    BROWSE
                                </span>
                                <button type="button" x-show="signatureName" @click.prevent="signatureName = ''; $refs.applicantSignature.value = ''" class="text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </label>
                        </div>
                        @error('applicant_signature') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('my-cto.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200 hover:-translate-y-0.5">
                            {{ __('Submit Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function ctoRequestForm(type, oldStart, oldEnd, minDate) {
            return {
                type,
                dateStart: oldStart || '',
                dateEnd: oldEnd || '',
                minDate,
                activePicker: null,
                currentMonth: null,
                init() {
                    this.currentMonth = this.toDate(this.dateStart || this.minDate);
                },
                openPicker(field) {
                    this.activePicker = field;
                    const selected = field === 'date_start' ? this.dateStart : this.dateEnd;
                    this.currentMonth = this.toDate(selected || this.dateStart || this.minDate);
                },
                selectDate(field, value) {
                    if (this.isDisabled(value, field)) return;

                    if (field === 'date_start') {
                        this.dateStart = value;

                        if (!this.dateEnd || this.toDate(this.dateEnd) < this.toDate(value)) {
                            this.dateEnd = value;
                        }
                    } else {
                        this.dateEnd = value;
                    }

                    this.activePicker = null;
                },
                calendarDays(field) {
                    const year = this.currentMonth.getFullYear();
                    const month = this.currentMonth.getMonth();
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const days = [];

                    for (let i = 0; i < firstDay.getDay(); i++) {
                        days.push({ key: `blank-${field}-${i}`, blank: true });
                    }

                    for (let day = 1; day <= lastDay.getDate(); day++) {
                        const date = new Date(year, month, day);
                        const value = this.toDateString(date);
                        const selected = field === 'date_start' ? value === this.dateStart : value === this.dateEnd;

                        days.push({
                            key: `${field}-${value}`,
                            blank: false,
                            day,
                            value,
                            selected,
                            disabled: this.isDisabled(value, field),
                        });
                    }

                    return days;
                },
                isDisabled(value, field) {
                    const date = this.toDate(value);
                    const day = date.getDay();

                    if (value < this.minDate || day === 0 || day === 6) return true;

                    return field === 'date_end'
                        && this.dateStart
                        && date < this.toDate(this.dateStart);
                },
                moveMonth(direction) {
                    this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + direction, 1);
                },
                get monthLabel() {
                    return this.currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                },
                formatDate(value) {
                    if (!value) return '';

                    return this.toDate(value).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                    });
                },
                toDate(value) {
                    return new Date(`${value}T00:00:00`);
                },
                toDateString(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');

                    return `${year}-${month}-${day}`;
                },
            };
        }
    </script>
</x-app-layout>
