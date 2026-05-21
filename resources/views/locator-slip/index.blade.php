<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 flex justify-between items-center border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800">My Locator Slips</h2>
                <a href="{{ route('locator-slips.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Create Locator Slip
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0b7a5a] text-white text-sm font-semibold">
                            <th class="py-3 px-6 whitespace-nowrap">Inclusive Date</th>
                            <th class="py-3 px-6 whitespace-nowrap">Destination</th>
                            <th class="py-3 px-6 whitespace-nowrap">Purpose</th>
                            <th class="py-3 px-6 whitespace-nowrap">Type</th>
                            <th class="py-3 px-6 whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($locatorSlips as $slip)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 group cursor-pointer" onclick="window.location='{{ route('locator-slips.show', $slip) }}'">
                                <td class="py-4 px-6 text-sm text-gray-700 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($slip->date_covered)->format('M. d, Y') }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 uppercase whitespace-nowrap">
                                    {{ $slip->destination ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 uppercase whitespace-nowrap">
                                    {{ $slip->purpose }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $slip->type ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-6 text-sm whitespace-nowrap">
                                    @php
                                        $statusClass = match(strtolower($slip->status)) {
                                            'approved' => 'bg-[#00c950] text-white',
                                            'rejected' => 'bg-red-500 text-white',
                                            'pending' => 'bg-yellow-400 text-white',
                                            'approved by chief' => 'bg-blue-500 text-white',
                                            default => 'bg-gray-400 text-white',
                                        };
                                    @endphp
                                    <span class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm {{ $statusClass }}">
                                        {{ ucfirst($slip->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    No locator slips found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

