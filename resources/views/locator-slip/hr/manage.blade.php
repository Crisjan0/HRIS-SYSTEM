<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800">Manage Locator Slips</h2>
            </div>

            <div class="p-6" x-data="{ tab: '{{ $tab }}' }">
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="tab = 'pending'"
                                :class="tab === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                            Pending Locator Slips
                        </button>
                        <button @click="tab = 'all'"
                                :class="tab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                            All Locator Slips
                        </button>
                    </nav>
                </div>

                <div x-show="tab === 'pending'" x-cloak>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Employee</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date Covered</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Purpose</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($pendingLocatorSlips as $slip)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 group cursor-pointer" onclick="window.location='{{ route('hr.locator-slips.show', $slip->id) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $slip->employee->firstname }} {{ $slip->employee->lastname }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $slip->date_covered }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 uppercase">{{ Str::limit($slip->purpose, 30) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($slip->time_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slip->time_to)->format('h:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                                                @if($slip->status == 'approved') bg-[#00c950] text-white @endif
                                                @if($slip->status == 'rejected') bg-red-500 text-white @endif
                                                @if(Str::contains($slip->status, 'pending')) bg-yellow-400 text-white @endif
                                                @if($slip->status == 'approved by chief') bg-blue-500 text-white @endif
                                            ">
                                                {{ ucfirst($slip->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('hr.locator-slips.show', $slip->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-700 hover:bg-blue-50 hover:text-blue-900 transition-colors" title="View" aria-label="View">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="sr-only">View</span>
                                                </a>
                                                @if(in_array(strtolower(Auth::user()->role), ['chief', 'regional director', 'regionaldirector', 'admin']))
                                                    <form action="{{ route('locator-slips.approve', $slip->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-green-600 hover:bg-green-50 hover:text-green-900 transition-colors" title="Approve" aria-label="Approve">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span class="sr-only">Approve</span>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('locator-slips.reject', $slip->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-600 hover:bg-red-50 hover:text-red-900 transition-colors" title="Reject" aria-label="Reject">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            <span class="sr-only">Reject</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">No pending locator slips found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="tab === 'all'" x-cloak>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Employee</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date Covered</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Purpose</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($allLocatorSlips as $slip)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 group cursor-pointer" onclick="window.location='{{ route('hr.locator-slips.show', $slip->id) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $slip->employee->firstname }} {{ $slip->employee->lastname }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $slip->date_covered }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 uppercase">{{ Str::limit($slip->purpose, 30) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($slip->time_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slip->time_to)->format('h:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                                                @if($slip->status == 'approved') bg-[#00c950] text-white @endif
                                                @if($slip->status == 'rejected') bg-red-500 text-white @endif
                                                @if(Str::contains($slip->status, 'pending')) bg-yellow-400 text-white @endif
                                                @if($slip->status == 'approved by chief') bg-blue-500 text-white @endif
                                            ">
                                                {{ ucfirst($slip->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('hr.locator-slips.show', $slip->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-700 hover:bg-blue-50 hover:text-blue-900 transition-colors" title="View" aria-label="View">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="sr-only">View</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">No locator slips found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
