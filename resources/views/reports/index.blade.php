<x-app-layout>
    <x-slot name="title">{{ __('Reports') }}</x-slot>

    <div class="py-12" x-data="reportsPreview()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('Reports') }}</h1>
                        <p class="text-sm text-gray-500 font-medium mt-1">{{ __('Generate HR summaries for employee records, attendance, and leave.') }}</p>
                    </div>
                    
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    @php
                        $reports = [
                            [
                                'slug' => 'employee',
                                'title' => 'Employee Summary',
                                'description' => 'Overview of employee records, divisions, positions, and account status.',
                                'icon_class' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                            ],
                            [
                                'slug' => 'attendance',
                                'title' => 'Attendance Summary',
                                'description' => 'Summary of DTR attendance, time logs, late records, and absences.',
                                'icon_class' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                            ],
                            [
                                'slug' => 'leave',
                                'title' => 'Leave Summary',
                                'description' => 'Summary of leave applications, balances, approvals, and filing trends.',
                                'icon_class' => 'bg-sky-50 text-sky-700 border-sky-100',
                                'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                            ],
                        ];
                    @endphp

                    @foreach($reports as $report)
                        <div class="rounded-2xl border border-gray-100 bg-gray-50/50 p-6 shadow-sm">
                            <div class="h-12 w-12 rounded-xl {{ $report['icon_class'] }} flex items-center justify-center mb-5 border">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $report['icon'] }}"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-black text-gray-900">{{ __($report['title']) }}</h2>
                            <p class="mt-2 text-sm text-gray-500 leading-relaxed min-h-[4rem]">{{ __($report['description']) }}</p>

                            <div class="mt-6 space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="block">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('From') }}</span>
                                        <input type="date" x-model="filters.{{ $report['slug'] }}.from" class="mt-1 w-full rounded-lg border-gray-200 bg-white text-xs text-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                    </label>
                                    <label class="block">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('To') }}</span>
                                        <input type="date" x-model="filters.{{ $report['slug'] }}.to" class="mt-1 w-full rounded-lg border-gray-200 bg-white text-xs text-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                    </label>
                                </div>
                                <button type="button" @click="openReport('{{ $report['slug'] }}', '{{ $report['title'] }}')" class="w-full rounded-xl bg-[#0038a8] px-4 py-3 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-blue-100 hover:bg-[#002f8f] active:scale-[0.99] transition">
                                    {{ __('Generate Report') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="modalOpen = false"></div>

            <div class="relative w-full max-w-5xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl border border-gray-100">
                <div class="sticky top-0 z-10 bg-white border-b border-gray-100 px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#0038a8]">{{ __('Sample Report Preview') }}</p>
                        <h2 class="text-xl font-black text-gray-900" x-text="selected.title"></h2>
                        <p class="text-xs text-gray-500 mt-1">
                            <span x-text="selected.from"></span>
                            <span> to </span>
                            <span x-text="selected.to"></span>
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" disabled class="rounded-lg bg-gray-100 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-not-allowed">
                            {{ __('Export PDF') }}
                        </button>
                        <button type="button" disabled class="rounded-lg bg-gray-100 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-not-allowed">
                            {{ __('Print') }}
                        </button>
                        <button type="button" @click="modalOpen = false" class="h-9 w-9 rounded-lg text-gray-400 hover:bg-gray-50 hover:text-gray-700">
                            <svg class="mx-auto w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <template x-for="metric in selected.metrics" :key="metric.label">
                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400" x-text="metric.label"></p>
                                <p class="mt-2 text-2xl font-black text-gray-900" x-text="metric.value"></p>
                                <p class="mt-1 text-xs font-bold text-gray-500" x-text="metric.note"></p>
                            </div>
                        </template>
                    </div>

                    <div class="rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-sm font-black uppercase tracking-widest text-gray-700">{{ __('Report Details') }}</h3>
                            <span class="text-[10px] font-black uppercase tracking-widest text-amber-600 bg-amber-50 border border-amber-100 rounded-full px-3 py-1">{{ __('Static Sample') }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-white text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    <tr>
                                        <template x-for="heading in selected.headings" :key="heading">
                                            <th class="px-5 py-3" x-text="heading"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="row in selected.rows" :key="row.join('-')">
                                        <tr>
                                            <template x-for="cell in row" :key="cell">
                                                <td class="px-5 py-4 font-bold text-gray-700 whitespace-nowrap" x-text="cell"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reportsPreview() {
            const today = new Date().toISOString().slice(0, 10);
            const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().slice(0, 10);

            return {
                modalOpen: false,
                filters: {
                    employee: { from: firstDay, to: today },
                    attendance: { from: firstDay, to: today },
                    leave: { from: firstDay, to: today },
                },
                selected: {
                    title: '',
                    from: '',
                    to: '',
                    metrics: [],
                    headings: [],
                    rows: [],
                },
                openReport(type, title) {
                    const filter = this.filters[type];
                    const samples = {
                        employee: {
                            metrics: [
                                { label: 'Total Employees', value: '128', note: 'Across all divisions' },
                                { label: 'Approved Accounts', value: '116', note: 'Active HRIS users' },
                                { label: 'Pending Accounts', value: '12', note: 'Awaiting HR action' },
                                { label: 'Divisions', value: '4', note: 'With employee records' },
                            ],
                            headings: ['Division', 'Employees', 'Approved', 'Pending'],
                            rows: [
                                ['Finance and Administrative', '38', '34', '4'],
                                ['Processing Division', '42', '39', '3'],
                                ['Protection Division', '29', '27', '2'],
                                ['Welfare and Reintegration', '19', '17', '2'],
                            ],
                        },
                        attendance: {
                            metrics: [
                                { label: 'Present', value: '1,842', note: 'Recorded DTR logs' },
                                { label: 'Late', value: '87', note: 'Sample late records' },
                                { label: 'Absent', value: '24', note: 'Sample absences' },
                                { label: 'Avg Hours', value: '7.82', note: 'Per work day' },
                            ],
                            headings: ['Employee', 'Present', 'Late', 'Absent'],
                            rows: [
                                ['Maria Reyes', '21', '1', '0'],
                                ['Glyza Sarmiento', '20', '2', '1'],
                                ['Juan Dela Cruz', '22', '0', '0'],
                                ['Ana Santos', '19', '3', '1'],
                            ],
                        },
                        leave: {
                            metrics: [
                                { label: 'Filed', value: '46', note: 'Leave applications' },
                                { label: 'Approved', value: '33', note: 'Completed approvals' },
                                { label: 'Pending', value: '9', note: 'Needs review' },
                                { label: 'Rejected', value: '4', note: 'Not approved' },
                            ],
                            headings: ['Leave Type', 'Filed', 'Approved', 'Pending'],
                            rows: [
                                ['Vacation Leave', '18', '13', '4'],
                                ['Sick Leave', '14', '12', '1'],
                                ['Special Privilege Leave', '8', '5', '2'],
                                ['Forced Leave', '6', '3', '3'],
                            ],
                        },
                    };

                    this.selected = {
                        title,
                        from: filter.from || 'Not selected',
                        to: filter.to || 'Not selected',
                        ...samples[type],
                    };
                    this.modalOpen = true;
                },
            };
        }
    </script>
</x-app-layout>
