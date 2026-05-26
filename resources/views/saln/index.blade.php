<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 flex justify-between items-center border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800">My SALN Records</h2>
                <a href="{{ route('salns.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    File New SALN
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">As of Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Filing Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Total Assets</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Total Liabilities</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Net Worth</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($salns as $saln)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 group cursor-pointer" onclick="window.location='{{ route('salns.show', $saln) }}'">
                                <td class="py-4 px-6 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $saln->as_of_date->format('F d, Y') }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 uppercase whitespace-nowrap">
                                    {{ str_replace('_', ' ', $saln->type_of_filing) }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 whitespace-nowrap">
                                    ₱{{ number_format($saln->total_assets, 2) }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 whitespace-nowrap">
                                    ₱{{ number_format($saln->total_liabilities, 2) }}
                                </td>
                                <td class="py-4 px-6 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                    ₱{{ number_format($saln->net_worth, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    No SALN records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
