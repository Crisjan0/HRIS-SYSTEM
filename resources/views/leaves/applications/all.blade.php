<x-app-layout>
    <x-slot name="title">{{ __('Leave Records History') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('leaves._manage-tabs')
            @include('leaves.applications._approval-tracker', ['leaves' => $leaves])

            <form id="leaveApplicationFilterForm" method="GET" action="{{ route('leave-applications.all') }}" data-filter-url="{{ route('leave-applications.all.filter') }}" class="mb-4 flex min-w-0 flex-col gap-2 rounded-xl border border-gray-100 bg-gray-50/70 p-2 sm:flex-row sm:items-center">
                <div class="relative min-w-0 sm:flex-1">
                    <label for="search" class="sr-only">{{ __('Search leave application') }}</label>
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                        </svg>
                    </span>
                    <input id="search" name="search" type="search" value="{{ $search }}" placeholder="{{ __('Search employee or leave type...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div class="sm:w-44 sm:shrink-0">
                    <label for="status" class="sr-only">{{ __('Status') }}</label>
                    <select id="status" name="status" class="block h-9 w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('All Statuses') }}</option>
                        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $value => $label)
                            <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>{{ __($label) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-48 sm:shrink-0">
                    <label for="sort" class="sr-only">{{ __('Sort') }}</label>
                    <select id="sort" name="sort" class="block h-9 w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="date_filed_desc" {{ $sort === 'date_filed_desc' ? 'selected' : '' }}>{{ __('Newest Filed') }}</option>
                        <option value="date_filed_asc" {{ $sort === 'date_filed_asc' ? 'selected' : '' }}>{{ __('Oldest Filed') }}</option>
                        <option value="employee_asc" {{ $sort === 'employee_asc' ? 'selected' : '' }}>{{ __('Name A-Z') }}</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 sm:shrink-0">
                    <a href="{{ route('leave-applications.all') }}" class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-xs font-bold uppercase tracking-wider text-gray-700 transition hover:bg-gray-50">
                        {{ __('Reset') }}
                    </a>
                </div>
            </form>

            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="w-[35%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Name</th>
                                <th scope="col" class="w-[30%] whitespace-nowrap px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Leave Type</th>
                                <th scope="col" class="w-[20%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Date Filed</th>
                                <th scope="col" class="w-[15%] px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leaveApplicationTableBody" class="divide-y divide-gray-100 bg-white">
                            @include('leaves.applications._rows', [
                                'leaves' => $leaves,
                                'actionMode' => 'view',
                                'emptyMessage' => __('No leave records found.'),
                            ])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('leaves.applications._filter-script')
</x-app-layout>
