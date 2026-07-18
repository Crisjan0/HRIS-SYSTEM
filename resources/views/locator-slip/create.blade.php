<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-5">
                <a href="{{ route('locator-slips.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Locator Slip</h2>
                <form action="{{ route('locator-slips.store') }}" method="POST" x-data="locatorSlipDateForm('{{ old('date_covered') }}', '{{ now()->toDateString() }}')">
                    @csrf
                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <x-weekday-date-picker field="date_covered" :label="__('Date Covered')" model="dateCovered" />
                    </div>

                    <div class="mb-4">
                        <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                        <input type="text" id="destination" name="destination" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="e.g. PANABO CITY">
                        @error('destination') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose</label>
                        <textarea id="purpose" name="purpose" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="e.g. PERSONAL"></textarea>
                        @error('purpose') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-700">Type</span>
                        <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:border-indigo-300">
                                <input type="radio" name="type" value="Official Business" class="border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('type') === 'Official Business' ? 'checked' : '' }} required>
                                {{ __('Official Business') }}
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:border-indigo-300">
                                <input type="radio" name="type" value="Personal" class="border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('type', 'Personal') === 'Personal' ? 'checked' : '' }} required>
                                {{ __('Personal') }}
                            </label>
                        </div>
                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="time_from" class="block text-sm font-medium text-gray-700">From (Time)</label>
                            <input type="time" id="time_from" name="time_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('time_from') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="time_to" class="block text-sm font-medium text-gray-700">To (Time)</label>
                            <input type="time" id="time_to" name="time_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('time_to') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('locator-slips.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function locatorSlipDateForm(initialDate, minDate) {
            return {
                dateCovered: initialDate || '',
                minDate,
                activePicker: null,
                currentMonth: new Date((initialDate || minDate) + 'T00:00:00'),
                openPicker() {
                    const seed = this.dateCovered || this.minDate;
                    this.currentMonth = new Date(seed + 'T00:00:00');
                    this.currentMonth.setDate(1);
                    this.activePicker = 'date_covered';
                },
                get monthLabel() {
                    return this.currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                },
                moveMonth(amount) {
                    this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + amount, 1);
                },
                toIso(date) {
                    return date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
                },
                formatDate(value) {
                    if (!value) return '';
                    return new Date(value + 'T00:00:00').toLocaleDateString('en-GB');
                },
                isDisabled(value) {
                    const date = new Date(value + 'T00:00:00');
                    const today = new Date(this.minDate + 'T00:00:00');
                    return date < today || date.getDay() === 0 || date.getDay() === 6;
                },
                calendarDays() {
                    const year = this.currentMonth.getFullYear();
                    const month = this.currentMonth.getMonth();
                    const first = new Date(year, month, 1);
                    const last = new Date(year, month + 1, 0);
                    const days = [];

                    for (let i = 0; i < first.getDay(); i++) {
                        days.push({ key: 'blank-' + i, blank: true });
                    }

                    for (let day = 1; day <= last.getDate(); day++) {
                        const date = new Date(year, month, day);
                        const value = this.toIso(date);
                        days.push({
                            key: value,
                            blank: false,
                            day,
                            value,
                            disabled: this.isDisabled(value),
                            selected: value === this.dateCovered,
                        });
                    }

                    return days;
                },
                selectDate(field, value) {
                    if (this.isDisabled(value)) return;
                    this.dateCovered = value;
                    this.activePicker = null;
                },
                
            };
        }
    </script>
</x-app-layout>
