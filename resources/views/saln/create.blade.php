<x-app-layout>
    @include('saln.partials.official-styles')

    @php
        $oldFilingType = old('type_of_filing', '');
        $oldAnnualYear = old('type_of_filing') === 'annual_filing' && old('as_of_date')
            ? \Carbon\Carbon::parse(old('as_of_date'))->format('Y')
            : '';
        $oldAssumptionDate = old('type_of_filing') === 'assumption_of_office' ? old('as_of_date', '') : '';
        $oldExitDate = old('type_of_filing') === 'exit' ? old('as_of_date', '') : '';
    @endphp

    <div class="py-10 bg-slate-50 min-h-screen font-sans" x-data="salnForm(@js([
        'children' => old('children', []),
        'real_properties' => old('real_properties', []),
        'personal_properties' => old('personal_properties', []),
        'liabilities' => old('liabilities', []),
        'business_interests' => old('business_interests', []),
        'relatives_in_gov' => old('relatives_in_gov', []),
        'no_business_interests' => old('has_business_interests') !== null ? ! filter_var(old('has_business_interests'), FILTER_VALIDATE_BOOLEAN) : true,
        'no_relatives_in_gov' => old('has_relatives_in_gov') !== null ? ! filter_var(old('has_relatives_in_gov'), FILTER_VALIDATE_BOOLEAN) : true,
        'multiple_marriages_na' => (bool) old('declarant_info.multiple_marriages_not_applicable'),
        'filing_type' => $oldFilingType,
        'assumption_date' => $oldAssumptionDate,
        'annual_year' => $oldAnnualYear,
        'exit_date' => $oldExitDate,
    ]))">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb / Header Actions -->
            <div class="mb-6">
                <a href="{{ route('salns.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg shadow-sm hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm animate-fade-in">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <p class="text-sm font-bold text-red-800">Please review and fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 mt-1 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('salns.store') }}" class="bg-white shadow-md rounded-2xl border border-slate-200/80 overflow-hidden" @submit="prepareSubmit($event)">
                @csrf

                <!-- Official Form Meta Header Header -->
                <div class="bg-slate-50 border-b border-slate-200 p-6 md:p-8">
                    @include('saln.partials.official-meta')
                </div>

                <div class="p-6 md:p-8 space-y-8">
                    
                    <!-- COMPLIANCE TYPE -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                        <span class="block text-xs font-bold text-slate-700 tracking-wider uppercase mb-4">Compliance For:</span>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Option 1 -->
                            <label class="flex flex-col p-4 bg-white border rounded-xl shadow-sm cursor-pointer hover:border-indigo-300 transition-all group relative" :class="filingType === 'assumption_of_office' ? 'border-indigo-600 ring-2 ring-indigo-600/10' : 'border-slate-200'">
                                <div class="flex items-center gap-3 mb-2">
                                    <input type="radio" name="type_of_filing" value="assumption_of_office" class="h-4 w-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" x-model="filingType" required>
                                    <span class="text-sm font-semibold text-slate-800">Assumption of Office</span>
                                </div>
                                <div class="mt-auto pt-2 border-t border-slate-100">
                                    <span class="text-[11px] text-slate-400 block mb-1">As of date:</span>
                                    <input type="date" class="w-full text-sm bg-slate-50 rounded-lg border border-slate-200 px-2.5 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" x-model="assumptionDate" :disabled="filingType !== 'assumption_of_office'" :required="filingType === 'assumption_of_office'">
                                </div>
                            </label>

                            <!-- Option 2 -->
                            <label class="flex flex-col p-4 bg-white border rounded-xl shadow-sm cursor-pointer hover:border-indigo-300 transition-all group relative" :class="filingType === 'annual_filing' ? 'border-indigo-600 ring-2 ring-indigo-600/10' : 'border-slate-200'">
                                <div class="flex items-center gap-3 mb-2">
                                    <input type="radio" name="type_of_filing" value="annual_filing" class="h-4 w-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" x-model="filingType">
                                    <span class="text-sm font-semibold text-slate-800">Annual Filing</span>
                                </div>
                                <div class="mt-auto pt-2 border-t border-slate-100">
                                    <span class="text-[11px] text-slate-400 block mb-1">As of December 31,</span>
                                    <input type="text" inputmode="numeric" maxlength="4" pattern="[0-9]{4}" placeholder="YYYY" class="w-full text-sm bg-slate-50 rounded-lg border border-slate-200 px-2.5 py-1.5 font-mono tracking-wider focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" x-model="annualYear" :disabled="filingType !== 'annual_filing'" :required="filingType === 'annual_filing'">
                                </div>
                            </label>

                            <!-- Option 3 -->
                            <label class="flex flex-col p-4 bg-white border rounded-xl shadow-sm cursor-pointer hover:border-indigo-300 transition-all group relative" :class="filingType === 'exit' ? 'border-indigo-600 ring-2 ring-indigo-600/10' : 'border-slate-200'">
                                <div class="flex items-center gap-3 mb-2">
                                    <input type="radio" name="type_of_filing" value="exit" class="h-4 w-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" x-model="filingType">
                                    <span class="text-sm font-semibold text-slate-800">Exit from Service</span>
                                </div>
                                <div class="mt-auto pt-2 border-t border-slate-100">
                                    <span class="text-[11px] text-slate-400 block mb-1">As of date:</span>
                                    <input type="date" class="w-full text-sm bg-slate-50 rounded-lg border border-slate-200 px-2.5 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" x-model="exitDate" :disabled="filingType !== 'exit'" :required="filingType === 'exit'">
                                </div>
                            </label>
                        </div>
                        <input type="hidden" name="as_of_date" :value="asOfDate">
                    </div>


                    <!-- DECLARANT & SPOUSE INFORMATION -->
                    <div class="space-y-6">
                        <!-- Declarant -->
                        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-[11px] font-bold uppercase tracking-wider">Declarant</span>
                                <h3 class="text-sm font-bold text-slate-800">Personal & Office Details</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Family Name *</label>
                                    <input type="text" name="declarant_info[family_name]" value="{{ old('declarant_info.family_name', $employee->lastname) }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">First Name *</label>
                                    <input type="text" name="declarant_info[first_name]" value="{{ old('declarant_info.first_name', $employee->firstname) }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Middle Initial</label>
                                    <input type="text" name="declarant_info[middle_initial]" value="{{ old('declarant_info.middle_initial', strtoupper(substr($employee->middlename ?? '', 0, 1))) }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" maxlength="3">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Position *</label>
                                    <input type="text" name="declarant_info[position]" value="{{ old('declarant_info.position', $employee->position) }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Agency/Office *</label>
                                    <input type="text" name="declarant_info[agency]" value="{{ old('declarant_info.agency', 'HRIS') }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Office Address *</label>
                                    <input type="text" name="declarant_info[office_address]" value="{{ old('declarant_info.office_address') }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                                </div>
                            </div>
                        </div>

                        <!-- Spouse -->
                        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[11px] font-bold uppercase tracking-wider">Spouse</span>
                                <h3 class="text-sm font-bold text-slate-800">Personal & Office Details <span class="text-xs font-normal text-slate-400">(If applicable)</span></h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Family Name</label>
                                    <input type="text" name="spouse_info[family_name]" value="{{ old('spouse_info.family_name') }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">First Name</label>
                                    <input type="text" name="spouse_info[first_name]" value="{{ old('spouse_info.first_name') }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Middle Initial</label>
                                    <input type="text" name="spouse_info[middle_initial]" value="{{ old('spouse_info.middle_initial') }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" maxlength="3">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Position</label>
                                    <input type="text" name="spouse_info[position]" value="{{ old('spouse_info.position') }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Agency/Office</label>
                                    <input type="text" name="spouse_info[agency]" value="{{ old('spouse_info.agency') }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Office Address</label>
                                    <input type="text" name="spouse_info[office_address]" value="{{ old('spouse_info.office_address') }}" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- FILING TYPE & MARRIAGE DETAILS -->
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 space-y-5">
                        <div>
                            <span class="block text-xs font-bold text-slate-700 tracking-wider uppercase mb-1">Filing Status:</span>
                            <p class="text-xs text-slate-400 mb-3">Spouses, who are both public officials or employees, may file the SALN jointly or separately.</p>
                            <div class="flex flex-wrap gap-4 bg-white p-3.5 rounded-lg border border-slate-200">
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                    <input type="radio" name="filing_status" value="joint" class="h-4 w-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" {{ old('filing_status') === 'joint' ? 'checked' : '' }} required>
                                    <span>Joint Filing</span>
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                    <input type="radio" name="filing_status" value="separate" class="h-4 w-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" {{ old('filing_status') === 'separate' ? 'checked' : '' }}>
                                    <span>Separate Filing</span>
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                    <input type="radio" name="filing_status" value="not_applicable" class="h-4 w-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" {{ old('filing_status') === 'not_applicable' ? 'checked' : '' }}>
                                    <span>Not Applicable</span>
                                </label>
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="block text-xs font-bold text-slate-700 tracking-wider uppercase">Multiple Marriages:</span>
                                <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 cursor-pointer">
                                    <input type="checkbox" name="declarant_info[multiple_marriages_not_applicable]" value="1" class="rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" x-model="multipleMarriagesNA" @change="clearSpouseNames()">
                                    <span>Not Applicable</span>
                                </label>
                            </div>
                            <p class="text-xs text-slate-400 mb-3">If with multiple marriages, indicate name(s) of spouses, otherwise check the "Not Applicable" box.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <input type="text" name="declarant_info[multiple_spouses][]" x-model="spouseName1" class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 disabled:bg-slate-100 disabled:text-slate-400" placeholder="Name of spouse (if applicable)" :disabled="multipleMarriagesNA">
                                <input type="text" name="declarant_info[multiple_spouses][]" x-model="spouseName2" class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 disabled:bg-slate-100 disabled:text-slate-400" placeholder="Name of spouse (if applicable)" :disabled="multipleMarriagesNA">
                            </div>
                        </div>
                    </div>


                    <!-- UNMARRIED CHILDREN BELOW 18 -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-slate-900 text-white px-4 py-3 rounded-xl shadow-sm">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider">Unmarried Children Below Eighteen (18) Years of Age</h3>
                                <p class="text-[11px] text-slate-400 font-normal">Living in Declarant's Household</p>
                            </div>
                            <button type="button" @click="addChild()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg> Add Child
                            </button>
                        </div>

                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm bg-white">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-600 uppercase">
                                        <th class="p-3 w-2/3">Name of Child</th>
                                        <th class="p-3 w-1/3 text-center">Age</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(child, index) in children" :key="child._id">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="p-2">
                                                <input type="text" :name="'children['+index+'][name]'" x-model="child.name" class="w-full text-sm bg-slate-50 rounded-lg border-slate-200 px-3 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            </td>
                                            <td class="p-2">
                                                <div class="flex items-center justify-center gap-3">
                                                    <input type="number" min="0" max="17" :name="'children['+index+'][age]'" x-model="child.age" class="w-20 text-center text-sm bg-slate-50 rounded-lg border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <button type="button" @click="removeChild(index)" class="text-xs font-semibold text-red-600 hover:text-red-800 hover:underline">Remove</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="children.length === 0">
                                        <td colspan="2" class="p-6 text-center text-sm text-slate-400 bg-slate-50/30 italic">No children added — click "+ Add Child" if applicable</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- FINANCIAL SECTION CONTAINER HEADER -->
                    <div class="border-t-2 border-dashed border-slate-200 pt-4">
                        <div class="text-center max-w-xl mx-auto mb-6">
                            <h2 class="text-lg font-bold text-slate-900">Assets, Liabilities and Networth</h2>
                            <p class="text-xs text-slate-500 mt-1">(Including those of the spouse and unmarried children below eighteen (18) years of age living in declarant's household)</p>
                        </div>
                    </div>


                    <!-- ASSETS: REAL PROPERTIES -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-slate-800 text-white px-4 py-2.5 rounded-xl shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider">1. Assets — a. Real Properties</h3>
                            <button type="button" @click="addRealProperty()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg> Add Row
                            </button>
                        </div>

                        <div class="border border-slate-200 rounded-xl overflow-x-auto shadow-sm bg-white">
                            <table class="w-full text-left min-w-[900px] border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                                        <th class="p-2.5 border-r border-slate-200">Description</th>
                                        <th class="p-2.5 border-r border-slate-200">Kind</th>
                                        <th class="p-2.5 border-r border-slate-200">Exact Location</th>
                                        <th class="p-2.5 border-r border-slate-200">Assessed Value</th>
                                        <th class="p-2.5 border-r border-slate-200">Current FMV</th>
                                        <th class="p-2.5 text-center border-r border-slate-200" colspan="2">Acquisition</th>
                                        <th class="p-2.5 text-center">Acquisition Cost</th>
                                    </tr>
                                    <tr class="bg-slate-100/60 border-b border-slate-200 font-semibold text-[10px] text-slate-500 uppercase">
                                        <td colspan="5" class="border-r border-slate-200"></td>
                                        <td class="p-1 text-center border-r border-slate-200 w-20">Year</td>
                                        <td class="p-1 text-center border-r border-slate-200 w-28">Mode</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(prop, index) in realProperties" :key="prop._id">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="p-1.5 border-r border-slate-100"><input type="text" :name="'real_properties['+index+'][description]'" x-model="prop.description" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1 focus:bg-white focus:ring-1 focus:ring-indigo-500"></td>
                                            <td class="p-1.5 border-r border-slate-100"><input type="text" :name="'real_properties['+index+'][kind]'" x-model="prop.kind" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1 focus:bg-white focus:ring-1 focus:ring-indigo-500"></td>
                                            <td class="p-1.5 border-r border-slate-100"><input type="text" :name="'real_properties['+index+'][location]'" x-model="prop.location" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1 focus:bg-white focus:ring-1 focus:ring-indigo-500"></td>
                                            <td class="p-1.5 border-r border-slate-100"><input type="number" step="0.01" min="0" :name="'real_properties['+index+'][assessed_value]'" x-model="prop.assessed_value" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1 focus:bg-white focus:ring-1 focus:ring-indigo-500"></td>
                                            <td class="p-1.5 border-r border-slate-100"><input type="number" step="0.01" min="0" :name="'real_properties['+index+'][fair_market_value]'" x-model="prop.fair_market_value" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1 focus:bg-white focus:ring-1 focus:ring-indigo-500"></td>
                                            <td class="p-1.5 border-r border-slate-100"><input type="text" :name="'real_properties['+index+'][acquisition_year]'" x-model="prop.acquisition_year" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1 text-center focus:bg-white focus:ring-1 focus:ring-indigo-500"></td>
                                            <td class="p-1.5 border-r border-slate-100"><input type="text" :name="'real_properties['+index+'][acquisition_mode]'" x-model="prop.acquisition_mode" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1 focus:bg-white focus:ring-1 focus:ring-indigo-500"></td>
                                            <td class="p-1.5">
                                                <div class="flex items-center gap-2">
                                                    <input type="number" step="0.01" min="0" :name="'real_properties['+index+'][acquisition_cost]'" x-model.number="prop.acquisition_cost" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1 text-right focus:bg-white focus:ring-1 focus:ring-indigo-500">
                                                    <button type="button" @click="removeRealProperty(index)" class="text-red-500 hover:text-red-700 px-1 font-semibold">✕</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="realProperties.length === 0">
                                        <td colspan="8" class="p-6 text-center text-sm text-slate-400 bg-slate-50/30 italic">No real properties added — click "+ Add Row" to add data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- ASSETS: PERSONAL PROPERTIES -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-slate-800 text-white px-4 py-2.5 rounded-xl shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider">b. Personal Properties</h3>
                            <button type="button" @click="addPersonalProperty()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg> Add Row
                            </button>
                        </div>

                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm bg-white">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                                        <th class="p-2.5 w-1/2">Description</th>
                                        <th class="p-2.5 text-center w-1/4">Acquisition Year</th>
                                        <th class="p-2.5 text-right w-1/4">Acquisition Cost / Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(prop, index) in personalProperties" :key="prop._id">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="p-1.5">
                                                <input type="text" :name="'personal_properties['+index+'][description]'" x-model="prop.description" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-3 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'personal_properties['+index+'][acquisition_year]'" x-model="prop.acquisition_year" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-3 py-1.5 text-center focus:bg-white focus:ring-1 focus:ring-indigo-500">
                                            </td>
                                            <td class="p-1.5">
                                                <div class="flex items-center gap-2">
                                                    <input type="number" step="0.01" min="0" :name="'personal_properties['+index+'][acquisition_cost]'" x-model.number="prop.acquisition_cost" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-3 py-1.5 text-right focus:bg-white focus:ring-1 focus:ring-indigo-500">
                                                    <button type="button" @click="removePersonalProperty(index)" class="text-red-500 hover:text-red-700 px-1 font-semibold">✕</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr class="bg-indigo-50/40 font-bold text-sm text-slate-800">
                                        <td colspan="2" class="p-3 text-right text-xs uppercase tracking-wider text-slate-500">Total Assets:</td>
                                        <td class="p-3 text-right text-indigo-700 font-mono" x-text="'₱ ' + totalAssets.toLocaleString('en-PH', {minimumFractionDigits: 2})"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- LIABILITIES -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-slate-800 text-white px-4 py-2.5 rounded-xl shadow-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider">2. Liabilities</h3>
                            <button type="button" @click="addLiability()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg> Add Row
                            </button>
                        </div>

                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm bg-white">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                                        <th class="p-2.5 w-1/3">Nature</th>
                                        <th class="p-2.5 w-1/3">Name of Creditors</th>
                                        <th class="p-2.5 text-right w-1/3">Outstanding Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(liab, index) in liabilities" :key="liab._id">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="p-1.5">
                                                <input type="text" :name="'liabilities['+index+'][nature]'" x-model="liab.nature" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-3 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'liabilities['+index+'][creditor]'" x-model="liab.creditor" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-3 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500">
                                            </td>
                                            <td class="p-1.5">
                                                <div class="flex items-center gap-2">
                                                    <input type="number" step="0.01" min="0" :name="'liabilities['+index+'][outstanding_balance]'" x-model.number="liab.outstanding_balance" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-3 py-1.5 text-right focus:bg-white focus:ring-1 focus:ring-indigo-500">
                                                    <button type="button" @click="removeLiability(index)" class="text-red-500 hover:text-red-700 px-1 font-semibold">✕</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr class="bg-red-50/40 font-bold text-sm text-slate-800">
                                        <td colspan="2" class="p-3 text-right text-xs uppercase tracking-wider text-slate-500">Total Liabilities:</td>
                                        <td class="p-3 text-right text-red-700 font-mono" x-text="'₱ ' + totalLiabilities.toLocaleString('en-PH', {minimumFractionDigits: 2})"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- NET WORTH SUMMARY BANNER -->
                    <div class="bg-gradient-to-r from-indigo-900 to-slate-900 text-white p-5 rounded-2xl shadow-md flex flex-col sm:flex-row justify-between items-center gap-3">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-widest text-indigo-200">Summary Computation</h4>
                            <p class="text-sm text-slate-300 mt-0.5">Net Worth = Total Assets less Total Liabilities</p>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Current Net Worth</span>
                            <span class="text-2xl font-black font-mono tracking-tight text-emerald-400" x-text="'₱ ' + netWorth.toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>


                    <!-- BUSINESS INTERESTS -->
                    <div class="space-y-3 border-t border-slate-200 pt-6">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-slate-800 text-white px-4 py-3 rounded-xl gap-3 shadow-sm">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider">Business Interests and Financial Connections</h3>
                                <p class="text-[11px] text-slate-400 font-normal">(Declarant / Spouse / Unmarried Children Below 18 living in Household)</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-2 text-xs font-semibold cursor-pointer text-slate-300 hover:text-white">
                                    <input type="checkbox" class="rounded text-indigo-500 border-slate-600 bg-slate-700 focus:ring-indigo-500 focus:ring-offset-slate-800" x-model="noBusinessInterests" @change="toggleNoBusiness()">
                                    <span>None / Not Applicable</span>
                                </label>
                                <button type="button" @click="addBusinessInterest()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors" x-show="!noBusinessInterests">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg> Add Row
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="has_business_interests" :value="noBusinessInterests ? 0 : 1">

                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm bg-white" x-show="!noBusinessInterests">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                                        <th class="p-2.5 w-1/4">Name of Entity / Enterprise</th>
                                        <th class="p-2.5 w-1/4">Business Address</th>
                                        <th class="p-2.5 w-1/4">Nature of Interest/Connection</th>
                                        <th class="p-2.5 w-1/4">Date of Acquisition</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(biz, index) in businessInterests" :key="biz._id">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="p-1.5"><input type="text" :name="'business_interests['+index+'][name]'" x-model="biz.name" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500" :disabled="noBusinessInterests"></td>
                                            <td class="p-1.5"><input type="text" :name="'business_interests['+index+'][address]'" x-model="biz.address" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500" :disabled="noBusinessInterests"></td>
                                            <td class="p-1.5"><input type="text" :name="'business_interests['+index+'][nature]'" x-model="biz.nature" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500" :disabled="noBusinessInterests"></td>
                                            <td class="p-1.5">
                                                <div class="flex items-center gap-2">
                                                    <input type="date" :name="'business_interests['+index+'][acquisition_date]'" x-model="biz.acquisition_date" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500" :disabled="noBusinessInterests">
                                                    <button type="button" @click="removeBusinessInterest(index)" class="text-red-500 hover:text-red-700 px-1 font-semibold">✕</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="businessInterests.length === 0">
                                        <td colspan="4" class="p-6 text-center text-sm text-slate-400 bg-slate-50/30 italic">Click "+ Add Row" or uncheck "None" to add business connection data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- RELATIVES IN GOVERNMENT -->
                    <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-slate-800 text-white px-4 py-3 rounded-xl gap-3 shadow-sm">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider">Relatives in the Government Service</h3>
                                <p class="text-[11px] text-slate-400 font-normal">(Within the 4th Degree of Consanguinity or Affinity, including Bilas, Balae and Inso)</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-2 text-xs font-semibold cursor-pointer text-slate-300 hover:text-white">
                                    <input type="checkbox" class="rounded text-indigo-500 border-slate-600 bg-slate-700 focus:ring-indigo-500 focus:ring-offset-slate-800" x-model="noRelativesInGov" @change="toggleNoRelatives()">
                                    <span>I do not know of any relative in gov't</span>
                                </label>
                                <button type="button" @click="addRelative()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors" x-show="!noRelativesInGov">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg> Add Row
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="has_relatives_in_gov" :value="noRelativesInGov ? 0 : 1">

                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm bg-white" x-show="!noRelativesInGov">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase">
                                        <th class="p-2.5 w-1/4">Name of Relative</th>
                                        <th class="p-2.5 w-1/4">Relationship</th>
                                        <th class="p-2.5 w-1/4">Position</th>
                                        <th class="p-2.5 w-1/4">Agency / Office &amp; Address</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(rel, index) in relatives" :key="rel._id">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="p-1.5"><input type="text" :name="'relatives_in_gov['+index+'][name]'" x-model="rel.name" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500" :disabled="noRelativesInGov"></td>
                                            <td class="p-1.5"><input type="text" :name="'relatives_in_gov['+index+'][relationship]'" x-model="rel.relationship" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500" :disabled="noRelativesInGov"></td>
                                            <td class="p-1.5"><input type="text" :name="'relatives_in_gov['+index+'][position]'" x-model="rel.position" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500" :disabled="noRelativesInGov"></td>
                                            <td class="p-1.5">
                                                <div class="flex items-center gap-2">
                                                    <input type="text" :name="'relatives_in_gov['+index+'][agency]'" x-model="rel.agency" class="w-full text-xs bg-slate-50 rounded border-slate-200 px-2 py-1.5 focus:bg-white focus:ring-1 focus:ring-indigo-500" :disabled="noRelativesInGov">
                                                    <button type="button" @click="removeRelative(index)" class="text-red-500 hover:text-red-700 px-1 font-semibold">✕</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="!noRelativesInGov && relatives.length === 0">
                                        <td colspan="4" class="p-6 text-center text-sm text-slate-400 bg-slate-50/30 italic">Click "+ Add Row" or uncheck the box above to add relatives</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- LEGAL CERTIFICATIONS & AUTHORIZATIONS -->
                    <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-5 space-y-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 text-amber-800 rounded-lg text-xs font-bold uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Legal Certification
                        </span>
                        <p class="text-xs text-slate-600 leading-relaxed text-justify">
                            I hereby certify that these are true and correct statements of my assets, liabilities, net worth, business interests and financial connections, including those of my spouse and unmarried children below eighteen (18) years of age living in my household, and that to the best of my knowledge, the above-enumerated are names of my relatives in the government within the fourth civil degree of consanguinity or affinity.
                        </p>
                        <p class="text-xs text-slate-600 leading-relaxed text-justify border-t border-amber-200/60 pt-3">
                            I hereby authorize the Ombudsman or his/her duly authorized representative to obtain and secure from all appropriate government agencies, including the Bureau of Internal Revenue such documents that may show my assets, liabilities, net worth, business interests and financial connections, to include those of my spouse and unmarried children below 18 years of age living with me in my household covering previous years to include the year I first assumed office in government.
                        </p>
                    </div>

                </div>

                <!-- SUBMIT ACTIONS FOOTER -->
                <div class="bg-slate-50 px-6 py-5 border-t border-slate-200 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('salns.index') }}" class="inline-flex justify-center items-center px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="inline-flex justify-center items-center px-7 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-semibold shadow-sm hover:bg-slate-800 focus:ring-4 focus:ring-slate-900/10 transition-colors">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function salnForm(initial = {}) {
            const uid = () => Date.now().toString(36) + Math.random().toString(36).slice(2);

            const withIds = (rows, defaults = {}) =>
                (Array.isArray(rows) ? rows : []).map(row => ({ ...defaults, ...row, _id: row._id || uid() }));

            const multipleSpouses = @json(old('declarant_info.multiple_spouses', []));

            return {
                children: withIds(initial.children),
                realProperties: withIds(initial.real_properties, { description: '', kind: '', location: '', acquisition_year: '', acquisition_mode: '', acquisition_cost: 0, assessed_value: '', fair_market_value: '' }),
                personalProperties: withIds(initial.personal_properties, { description: '', acquisition_year: '', acquisition_cost: 0 }),
                liabilities: withIds(initial.liabilities, { nature: '', creditor: '', outstanding_balance: 0 }),
                businessInterests: withIds(initial.business_interests, { name: '', address: '', nature: '', acquisition_date: '' }),
                relatives: withIds(initial.relatives_in_gov, { name: '', relationship: '', position: '', agency: '' }),
                noBusinessInterests: initial.no_business_interests ?? true,
                noRelativesInGov: initial.no_relatives_in_gov ?? true,
                multipleMarriagesNA: initial.multiple_marriages_na ?? false,
                spouseName1: multipleSpouses[0] ?? '',
                spouseName2: multipleSpouses[1] ?? '',
                filingType: initial.filing_type ?? '',
                assumptionDate: initial.assumption_date ?? '',
                annualYear: initial.annual_year ?? '',
                exitDate: initial.exit_date ?? '',

                get asOfDate() {
                    if (this.filingType === 'assumption_of_office') return this.assumptionDate;
                    if (this.filingType === 'annual_filing') return this.annualYear ? `${this.annualYear}-12-31` : '';
                    if (this.filingType === 'exit') return this.exitDate;
                    return '';
                },

                get totalRealAssets() {
                    return this.realProperties.reduce((sum, p) => sum + (Number(p.acquisition_cost) || 0), 0);
                },

                get totalPersonalAssets() {
                    return this.personalProperties.reduce((sum, p) => sum + (Number(p.acquisition_cost) || 0), 0);
                },

                get totalAssets() {
                    return this.totalRealAssets + this.totalPersonalAssets;
                },

                get totalLiabilities() {
                    return this.liabilities.reduce((sum, l) => sum + (Number(l.outstanding_balance) || 0), 0);
                },

                get netWorth() {
                    return this.totalAssets - this.totalLiabilities;
                },

                clearSpouseNames() {
                    if (this.multipleMarriagesNA) {
                        this.spouseName1 = '';
                        this.spouseName2 = '';
                    }
                },

                addChild() { this.children.push({ _id: uid(), name: '', age: '' }); },
                removeChild(i) { this.children.splice(i, 1); },

                addRealProperty() { this.realProperties.push({ _id: uid(), description: '', kind: '', location: '', acquisition_year: '', acquisition_mode: '', acquisition_cost: 0, assessed_value: '', fair_market_value: '' }); },
                removeRealProperty(i) { this.realProperties.splice(i, 1); },

                addPersonalProperty() { this.personalProperties.push({ _id: uid(), description: '', acquisition_year: '', acquisition_cost: 0 }); },
                removePersonalProperty(i) { this.personalProperties.splice(i, 1); },

                addLiability() { this.liabilities.push({ _id: uid(), nature: '', creditor: '', outstanding_balance: 0 }); },
                removeLiability(i) { this.liabilities.splice(i, 1); },

                addBusinessInterest() { this.businessInterests.push({ _id: uid(), name: '', address: '', nature: '', acquisition_date: '' }); },
                removeBusinessInterest(i) { this.businessInterests.splice(i, 1); },

                addRelative() { this.relatives.push({ _id: uid(), name: '', relationship: '', position: '', agency: '' }); },
                removeRelative(i) { this.relatives.splice(i, 1); },

                toggleNoBusiness() { if (this.noBusinessInterests) this.businessInterests = []; else if (this.businessInterests.length === 0) this.addBusinessInterest(); },
                toggleNoRelatives() { if (this.noRelativesInGov) this.relatives = []; else if (this.relatives.length === 0) this.addRelative(); },

                prepareSubmit(e) {
                    // Custom pre-submission handlers can go here if needed
                }
            };
        }
    </script>
</x-app-layout>
