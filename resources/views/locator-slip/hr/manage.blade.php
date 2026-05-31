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
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('hr.locator-slips.show', $slip->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold uppercase tracking-wider text-xs">View</a>
                                                @if(in_array(strtolower(Auth::user()->role), ['chief', 'regional director', 'regionaldirector', 'admin']))
                                                    <form action="{{ route('locator-slips.approve', $slip->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-green-600 hover:text-green-900 font-bold uppercase tracking-wider text-xs">Approve</button>
                                                    </form>
                                                    <form action="{{ route('locator-slips.reject', $slip->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold uppercase tracking-wider text-xs">Reject</button>
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
                                            <a href="{{ route('hr.locator-slips.show', $slip->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold uppercase tracking-wider text-xs">View</a>
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
