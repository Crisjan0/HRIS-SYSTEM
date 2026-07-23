<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Locator Slip</h2>

            <form action="{{ route('locator-slips.update', $locatorSlip) }}" method="POST" x-data="locatorSlipDateForm('{{ old('date_covered', $locatorSlip->date_covered) }}', '{{ now()->toDateString() }}')">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-weekday-date-picker field="date_covered" :label="__('Date Covered')" model="dateCovered" />
                    </div>
                    <div>
                        <span class="block font-medium text-sm text-gray-700">Type</span>
                        <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:border-indigo-300">
                                <input type="radio" name="type" value="Official Business" class="border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('type', $locatorSlip->type) === 'Official Business' ? 'checked' : '' }} required>
                                {{ __('Official Business') }}
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:border-indigo-300">
                                <input type="radio" name="type" value="Pass Slip" class="border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('type', $locatorSlip->type === 'Personal' ? 'Pass Slip' : $locatorSlip->type) === 'Pass Slip' ? 'checked' : '' }} required>
                                {{ __('Pass Slip') }}
                            </label>
                        </div>
                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="purpose" class="block font-medium text-sm text-gray-700">Purpose</label>
                        <input type="text" name="purpose" id="purpose" value="{{ old('purpose', $locatorSlip->purpose) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="time_from" class="block font-medium text-sm text-gray-700">Time From</label>
                        <input type="time" name="time_from" id="time_from" value="{{ old('time_from', $locatorSlip->time_from) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="time_to" class="block font-medium text-sm text-gray-700">Time To</label>
                        <input type="time" name="time_to" id="time_to" value="{{ old('time_to', $locatorSlip->time_to) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <a href="{{ route('locator-slips.show', $locatorSlip) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Update Locator Slip
                    </button>
                </div>
            </form>
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
