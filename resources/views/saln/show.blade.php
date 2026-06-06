<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 p-8 print:p-0 print:shadow-none print:border-none">
                
                <!-- Print Button -->
                <div class="flex justify-end mb-6 print:hidden">
                    <button onclick="window.print()" class="px-6 py-2 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700">Print SALN</button>
                </div>

                <!-- Document Header -->
                <div class="text-center mb-8">
                    <h1 class="text-xl font-bold uppercase underline tracking-wider">Sworn Statement of Assets, Liabilities, and Net Worth</h1>
                    <p class="text-sm">(As required by R.A. No. 6713)</p>
                    
                    <div class="mt-6 flex justify-center gap-12 font-bold text-sm">
                        <label class="flex items-center gap-2"><input type="checkbox" checked disabled> {{ str_replace('_', ' ', Str::title($saln->type_of_filing)) }}</label>
                        <span>as of {{ $saln->as_of_date->format('F d, Y') }}</span>
                    </div>
                </div>

                <!-- Declarant & Spouse Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-8 text-sm">
                    <div>
                        <div class="grid grid-cols-4 font-bold border-b-2 border-black pb-1 mb-2">
                            <div class="col-span-1">DECLARANT:</div>
                            <div class="col-span-1 text-center">{{ $saln->declarant_info['family_name'] ?? '' }}</div>
                            <div class="col-span-1 text-center">{{ $saln->declarant_info['first_name'] ?? '' }}</div>
                            <div class="col-span-1 text-center">{{ $saln->declarant_info['middle_initial'] ?? '' }}</div>
                        </div>
                        <div class="grid grid-cols-4 text-xs text-gray-500 text-center mb-4">
                            <div></div>
                            <div>(Family Name)</div>
                            <div>(First Name)</div>
                            <div>(M.I.)</div>
                        </div>

                        <div class="grid grid-cols-4 font-bold border-b-2 border-black pb-1 mb-2">
                            <div class="col-span-1">SPOUSE:</div>
                            <div class="col-span-1 text-center">{{ $saln->spouse_info['family_name'] ?? 'N/A' }}</div>
                            <div class="col-span-1 text-center">{{ $saln->spouse_info['first_name'] ?? 'N/A' }}</div>
                            <div class="col-span-1 text-center">{{ $saln->spouse_info['middle_initial'] ?? 'N/A' }}</div>
                        </div>
                        <div class="grid grid-cols-4 text-xs text-gray-500 text-center">
                            <div></div>
                            <div>(Family Name)</div>
                            <div>(First Name)</div>
                            <div>(M.I.)</div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex border-b border-gray-400 pb-1">
                            <div class="w-1/3 font-bold">POSITION:</div>
                            <div class="w-2/3">{{ $saln->declarant_info['position'] ?? '' }}</div>
                        </div>
                        <div class="flex border-b border-gray-400 pb-1">
                            <div class="w-1/3 font-bold">AGENCY/OFFICE:</div>
                            <div class="w-2/3">{{ $saln->declarant_info['agency'] ?? '' }}</div>
                        </div>
                        <div class="flex border-b border-gray-400 pb-1">
                            <div class="w-1/3 font-bold">OFFICE ADDRESS:</div>
                            <div class="w-2/3">{{ $saln->declarant_info['office_address'] ?? '' }}</div>
                        </div>
                    </div>
                </div>

                <div class="text-sm font-bold mb-6">
                    <div>POSITION: {{ $saln->spouse_info['position'] ?? 'N/A' }}</div>
                    <div>AGENCY/OFFICE: {{ $saln->spouse_info['agency'] ?? 'N/A' }}</div>
                    <div>OFFICE ADDRESS: {{ $saln->spouse_info['office_address'] ?? 'N/A' }}</div>
                </div>

                <!-- Children -->
                <div class="mb-8">
                    <h3 class="font-bold underline text-center mb-4">UNMARRIED CHILDREN BELOW EIGHTEEN (18) YEARS OF AGE LIVING IN DECLARANT'S HOUSEHOLD</h3>
                    <table class="w-full text-sm border-collapse border border-black">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-black p-2 w-2/3">NAME OF CHILD</th>
                                <th class="border border-black p-2 w-1/3">AGE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($saln->children ?? [] as $child)
                                <tr>
                                    <td class="border border-black p-2 text-center">{{ $child['name'] }}</td>
                                    <td class="border border-black p-2 text-center">{{ $child['age'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border border-black p-2 text-center">N/A</td>
                                    <td class="border border-black p-2 text-center">N/A</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Assets & Liabilities -->
                <div class="mb-8">
                    <h2 class="font-bold text-center underline text-lg mb-4">ASSETS, LIABILITIES AND NET WORTH</h2>
                    
                    <h3 class="font-bold mb-2">1. ASSETS</h3>
                    <div class="ml-4 mb-4">
                        <h4 class="font-bold mb-2">a. Real Properties</h4>
                        <table class="w-full text-xs border-collapse border border-black">
                            <thead class="bg-gray-100 text-center">
                                <tr>
                                    <th class="border border-black p-2">DESCRIPTION</th>
                                    <th class="border border-black p-2">KIND</th>
                                    <th class="border border-black p-2">EXACT LOCATION</th>
                                    <th class="border border-black p-2">ASSESSED VALUE</th>
                                    <th class="border border-black p-2">CURRENT FAIR MARKET VALUE</th>
                                    <th class="border border-black p-2">ACQUISITION YEAR</th>
                                    <th class="border border-black p-2">ACQUISITION MODE</th>
                                    <th class="border border-black p-2">ACQUISITION COST</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($saln->real_properties ?? [] as $prop)
                                    <tr class="text-center">
                                        <td class="border border-black p-1">{{ $prop['description'] }}</td>
                                        <td class="border border-black p-1">{{ $prop['kind'] }}</td>
                                        <td class="border border-black p-1">{{ $prop['location'] }}</td>
                                        <td class="border border-black p-1">₱{{ number_format((float)($prop['assessed_value'] ?? 0), 2) }}</td>
                                        <td class="border border-black p-1">₱{{ number_format((float)($prop['fair_market_value'] ?? 0), 2) }}</td>
                                        <td class="border border-black p-1">{{ $prop['acquisition_year'] }}</td>
                                        <td class="border border-black p-1">{{ $prop['acquisition_mode'] }}</td>
                                        <td class="border border-black p-1 font-bold">₱{{ number_format((float)($prop['acquisition_cost'] ?? 0), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="border border-black p-2 text-center text-gray-500">N/A</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="ml-4 mb-4">
                        <h4 class="font-bold mb-2">b. Personal Properties</h4>
                        <table class="w-full text-xs border-collapse border border-black">
                            <thead class="bg-gray-100 text-center">
                                <tr>
                                    <th class="border border-black p-2 w-1/2">DESCRIPTION</th>
                                    <th class="border border-black p-2">ACQUISITION YEAR</th>
                                    <th class="border border-black p-2">ACQUISITION COST / AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($saln->personal_properties ?? [] as $prop)
                                    <tr class="text-center">
                                        <td class="border border-black p-1">{{ $prop['description'] }}</td>
                                        <td class="border border-black p-1">{{ $prop['acquisition_year'] }}</td>
                                        <td class="border border-black p-1 font-bold">₱{{ number_format((float)($prop['acquisition_cost'] ?? 0), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="border border-black p-2 text-center text-gray-500">N/A</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="2" class="border border-black p-2 text-right font-bold">TOTAL ASSETS:</td>
                                    <td class="border border-black p-2 text-center font-bold text-lg bg-gray-100">₱{{ number_format($saln->total_assets, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="font-bold mb-2 mt-8">2. LIABILITIES</h3>
                    <div class="ml-4 mb-8">
                        <table class="w-full text-xs border-collapse border border-black">
                            <thead class="bg-gray-100 text-center">
                                <tr>
                                    <th class="border border-black p-2 w-1/3">NATURE</th>
                                    <th class="border border-black p-2 w-1/3">NAME OF CREDITORS</th>
                                    <th class="border border-black p-2 w-1/3">OUTSTANDING BALANCE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($saln->liabilities ?? [] as $liab)
                                    <tr class="text-center">
                                        <td class="border border-black p-1">{{ $liab['nature'] }}</td>
                                        <td class="border border-black p-1">{{ $liab['creditor'] }}</td>
                                        <td class="border border-black p-1 font-bold">₱{{ number_format((float)($liab['outstanding_balance'] ?? 0), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="border border-black p-2 text-center text-gray-500">N/A</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="2" class="border border-black p-2 text-right font-bold">TOTAL LIABILITIES:</td>
                                    <td class="border border-black p-2 text-center font-bold text-lg bg-gray-100">₱{{ number_format($saln->total_liabilities, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t-4 border-black pt-4 flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-xl font-black uppercase">Net Worth (Total Assets - Total Liabilities):</h2>
                        <h2 class="text-2xl font-black text-indigo-700">₱{{ number_format($saln->net_worth, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
