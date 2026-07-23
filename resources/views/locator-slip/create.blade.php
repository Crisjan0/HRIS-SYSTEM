<x-app-layout>
    <div class="min-h-screen bg-gray-100 px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">

            {{-- Locator Slip Card --}}
            <div class="overflow-hidden border border-gray-300 bg-white shadow-lg">

                {{-- Green Header --}}
                <div class="flex items-center justify-between bg-emerald-700 px-5 py-4">
                    <h2 class="text-lg font-bold text-white">
                        Create Locator Slip
                    </h2>

                    <a
                        href="{{ route('locator-slips.index') }}"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white transition hover:bg-white/15"
                        aria-label="{{ __('Close') }}"
                        title="{{ __('Close') }}"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </a>
                </div>

                <form
                    action="{{ route('locator-slips.store') }}"
                    method="POST"
                    x-data="locatorSlipDateForm(
                        '{{ old('date_covered') }}',
                        '{{ now()->toDateString() }}'
                    )"
                >
                    @csrf

                    <div class="px-6 py-8 sm:px-9">

                        {{-- Success Message --}}
                        @if (session()->has('message'))
                            <div
                                class="mb-6 border-l-4 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
                                role="alert"
                            >
                                {{ session('message') }}
                            </div>
                        @endif

                        {{-- Type --}}
                        <div class="mb-10 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-10">
                            <label class="flex cursor-pointer items-center gap-3 text-sm font-medium uppercase text-gray-600">
                                <input
                                    type="radio"
                                    name="type"
                                    value="Official Business"
                                    class="h-5 w-5 rounded-sm border-gray-400 text-emerald-600 focus:ring-emerald-500"
                                    {{ old('type') === 'Official Business' ? 'checked' : '' }}
                                    required
                                >

                                <span>{{ __('Official Business') }}</span>
                            </label>

                            <label class="flex cursor-pointer items-center gap-3 text-sm font-medium uppercase text-gray-600">
                                <input
                                    type="radio"
                                    name="type"
                                    value="Pass Slip"
                                    class="h-5 w-5 rounded-sm border-gray-400 text-emerald-600 focus:ring-emerald-500"
                                    {{ old('type', 'Pass Slip') === 'Pass Slip' ? 'checked' : '' }}
                                    required
                                >

                                <span>{{ __('Pass Slip') }}</span>
                            </label>
                        </div>

                        @error('type')
                            <p class="-mt-7 mb-6 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="space-y-7">

                            {{-- Destination --}}
                            <div class="grid grid-cols-1 items-end gap-2 sm:grid-cols-[190px_minmax(0,1fr)] sm:gap-6">
                                <label
                                    for="destination"
                                    class="text-sm font-medium text-gray-500"
                                >
                                    Destination:
                                </label>

                                <div>
                                    <input
                                        type="text"
                                        id="destination"
                                        name="destination"
                                        value="{{ old('destination') }}"
                                        placeholder="e.g. PANABO CITY"
                                        class="block w-full border-0 border-b border-gray-800 bg-transparent px-0 py-2 text-sm text-gray-700 placeholder-gray-400 shadow-none focus:border-emerald-600 focus:ring-0"
                                    >

                                    @error('destination')
                                        <p class="mt-1 text-xs text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Purpose --}}
                            <div class="grid grid-cols-1 items-start gap-2 sm:grid-cols-[190px_minmax(0,1fr)] sm:gap-6">
                                <label
                                    for="purpose"
                                    class="pt-2 text-sm font-medium text-gray-500"
                                >
                                    Purpose:
                                </label>

                                <div>
                                    <textarea
                                        id="purpose"
                                        name="purpose"
                                        rows="2"
                                        placeholder="e.g. Official business purpose or pass slip reason"
                                        class="block w-full resize-none border-0 border-b border-gray-800 bg-transparent px-0 py-2 text-sm text-gray-700 placeholder-gray-400 shadow-none focus:border-emerald-600 focus:ring-0"
                                    >{{ old('purpose') }}</textarea>

                                    @error('purpose')
                                        <p class="mt-1 text-xs text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Date Covered --}}
                            <div class="grid grid-cols-1 items-start gap-2 sm:grid-cols-[190px_minmax(0,1fr)] sm:gap-6">
                                <span class="pt-2 text-sm font-medium text-gray-500">
                                    Date Covered:
                                </span>

                                <div class="locator-date-field">
                                    <x-weekday-date-picker
                                        field="date_covered"
                                        :label="__('Date Covered')"
                                        model="dateCovered"
                                    />

                                    @error('date_covered')
                                        <p class="mt-1 text-xs text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Time Fields --}}
                            <div class="grid grid-cols-1 gap-6 pt-2 sm:grid-cols-2">
                                {{-- From Time --}}
                                <div class="grid grid-cols-[65px_minmax(0,1fr)] items-end gap-4">
                                    <label
                                        for="time_from"
                                        class="pb-2 text-sm font-medium uppercase text-gray-500"
                                    >
                                        From:
                                    </label>

                                    <div>
                                        <input
                                            type="time"
                                            id="time_from"
                                            name="time_from"
                                            value="{{ old('time_from', now()->format('H:i')) }}"
                                            class="block w-full border-0 border-b border-gray-800 bg-transparent px-0 py-2 text-sm text-gray-700 shadow-none focus:border-emerald-600 focus:ring-0"
                                        >

                                        @error('time_from')
                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- To Time --}}
                                <div class="grid grid-cols-[45px_minmax(0,1fr)] items-end gap-4">
                                    <label
                                        for="time_to"
                                        class="pb-2 text-sm font-medium uppercase text-gray-500"
                                    >
                                        To:
                                    </label>

                                    <div>
                                        <input
                                            type="time"
                                            id="time_to"
                                            name="time_to"
                                            value="{{ old('time_to') }}"
                                            class="block w-full border-0 border-b border-gray-800 bg-transparent px-0 py-2 text-sm text-gray-700 shadow-none focus:border-emerald-600 focus:ring-0"
                                        >

                                        @error('time_to')
                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="flex flex-col-reverse gap-3 border-t border-gray-300 bg-white px-5 py-4 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('locator-slips.index') }}"
                            class="inline-flex items-center justify-center gap-2 border border-red-500 bg-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-red-500 transition hover:bg-red-50"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <circle cx="12" cy="12" r="9" stroke-width="2"></circle>
                                <path
                                    stroke-linecap="round"
                                    stroke-width="2"
                                    d="M9 9l6 6m0-6l-6 6"
                                ></path>
                            </svg>

                            {{ __('Cancel') }}
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-emerald-700 px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /*
         * Adjust the reusable date-picker component so it blends
         * with the underlined locator-slip layout.
         */
        .locator-date-field > div {
            margin: 0 !important;
        }

        .locator-date-field label {
            display: none !important;
        }

        .locator-date-field input {
            width: 100% !important;
            border: 0 !important;
            border-bottom: 1px solid #1f2937 !important;
            border-radius: 0 !important;
            background-color: transparent !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            box-shadow: none !important;
        }

        .locator-date-field input:focus {
            border-bottom-color: #059669 !important;
            box-shadow: none !important;
            outline: none !important;
        }
    </style>

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
                    return this.currentMonth.toLocaleDateString('en-US', {
                        month: 'long',
                        year: 'numeric'
                    });
                },

                moveMonth(amount) {
                    this.currentMonth = new Date(
                        this.currentMonth.getFullYear(),
                        this.currentMonth.getMonth() + amount,
                        1
                    );
                },

                toIso(date) {
                    return date.getFullYear()
                        + '-'
                        + String(date.getMonth() + 1).padStart(2, '0')
                        + '-'
                        + String(date.getDate()).padStart(2, '0');
                },

                formatDate(value) {
                    if (!value) return '';

                    return new Date(value + 'T00:00:00')
                        .toLocaleDateString('en-GB');
                },

                isDisabled(value) {
                    const date = new Date(value + 'T00:00:00');
                    const today = new Date(this.minDate + 'T00:00:00');

                    return date < today
                        || date.getDay() === 0
                        || date.getDay() === 6;
                },

                calendarDays() {
                    const year = this.currentMonth.getFullYear();
                    const month = this.currentMonth.getMonth();
                    const first = new Date(year, month, 1);
                    const last = new Date(year, month + 1, 0);
                    const days = [];

                    for (let i = 0; i < first.getDay(); i++) {
                        days.push({
                            key: 'blank-' + i,
                            blank: true
                        });
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
                            selected: value === this.dateCovered
                        });
                    }

                    return days;
                },

                selectDate(field, value) {
                    if (this.isDisabled(value)) return;

                    this.dateCovered = value;
                    this.activePicker = null;
                }
            };
        }
    </script>
</x-app-layout>