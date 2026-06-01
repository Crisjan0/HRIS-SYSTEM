<x-app-layout>
    @include('saln.partials.official-styles')

    <div class="py-12" x-data="salnForm()">
        <div class="max-w-[900px] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl sm:rounded-lg border border-gray-200 p-6 md:p-10 saln-official">

                @include('saln.partials.official-meta')

                <form method="POST" action="{{ route('salns.store') }}">
                    @csrf

                    <p class="font-bold text-[11px] uppercase mb-2">Compliance For:</p>
                    <div class="saln-compliance-row mb-4 border-b border-black pb-3">
                        <label class="saln-compliance-option saln-check-label">
                            <input type="radio" name="type_of_filing" value="assumption_of_office" class="saln-native-check" x-model="filingType" required>
                            <span class="saln-box"></span>
                            <span>Assumption of office as of</span>
                            <input type="date" class="saln-inline-blank w-[128px]" x-model="assumptionDate" :disabled="filingType !== 'assumption_of_office'" :required="filingType === 'assumption_of_office'">
                        </label>
                        <label class="saln-compliance-option saln-check-label">
                            <input type="radio" name="type_of_filing" value="annual_filing" class="saln-native-check" x-model="filingType">
                            <span class="saln-box"></span>
                            <span>Annual filing as of December 31,</span>
                            <input type="text" inputmode="numeric" maxlength="4" pattern="[0-9]{4}" placeholder="____" class="saln-inline-blank saln-inline-year" x-model="annualYear" :disabled="filingType !== 'annual_filing'" :required="filingType === 'annual_filing'">
                        </label>
                        <label class="saln-compliance-option saln-check-label">
                            <input type="radio" name="type_of_filing" value="exit" class="saln-native-check" x-model="filingType">
                            <span class="saln-box"></span>
                            <span>Exit as of</span>
                            <input type="date" class="saln-inline-blank w-[128px]" x-model="exitDate" :disabled="filingType !== 'exit'" :required="filingType === 'exit'">
                        </label>
                    </div>
                    <input type="hidden" name="as_of_date" :value="asOfDate">

                    {{-- Declarant & Spouse --}}
                    <table class="mb-2">
                        <tr>
                            <td class="w-[12%] font-bold align-top">DECLARANT:</td>
                            <td class="w-[22%] text-center align-top">
                                <input type="text" name="declarant_info[family_name]" class="saln-input text-center" required>
                                <span class="text-[8px] block">(Family Name)</span>
                            </td>
                            <td class="w-[22%] text-center align-top">
                                <input type="text" name="declarant_info[first_name]" class="saln-input text-center" required>
                                <span class="text-[8px] block">(First Name)</span>
                            </td>
                            <td class="w-[8%] text-center align-top">
                                <input type="text" name="declarant_info[middle_initial]" class="saln-input text-center" maxlength="3">
                                <span class="text-[8px] block">(M.I.)</span>
                            </td>
                            <td class="w-[36%] align-top">
                                <div class="flex mb-1 items-center"><span class="w-28 font-bold shrink-0">POSITION:</span><input type="text" name="declarant_info[position]" class="saln-input flex-1" required></div>
                                <div class="flex mb-1 items-center"><span class="w-28 font-bold shrink-0">AGENCY/OFFICE:</span><input type="text" name="declarant_info[agency]" class="saln-input flex-1" required></div>
                                <div class="flex items-center"><span class="w-28 font-bold shrink-0">OFFICE ADDRESS:</span><input type="text" name="declarant_info[office_address]" class="saln-input flex-1" required></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold align-top">SPOUSE:</td>
                            <td class="text-center align-top">
                                <input type="text" name="spouse_info[family_name]" class="saln-input text-center">
                                <span class="text-[8px] block">(Family Name)</span>
                            </td>
                            <td class="text-center align-top">
                                <input type="text" name="spouse_info[first_name]" class="saln-input text-center">
                                <span class="text-[8px] block">(First Name)</span>
                            </td>
                            <td class="text-center align-top">
                                <input type="text" name="spouse_info[middle_initial]" class="saln-input text-center" maxlength="3">
                                <span class="text-[8px] block">(M.I.)</span>
                            </td>
                            <td class="align-top">
                                <div class="flex mb-1 items-center"><span class="w-28 font-bold shrink-0">POSITION:</span><input type="text" name="spouse_info[position]" class="saln-input flex-1"></div>
                                <div class="flex mb-1 items-center"><span class="w-28 font-bold shrink-0">AGENCY/OFFICE:</span><input type="text" name="spouse_info[agency]" class="saln-input flex-1"></div>
                                <div class="flex items-center"><span class="w-28 font-bold shrink-0">OFFICE ADDRESS:</span><input type="text" name="spouse_info[office_address]" class="saln-input flex-1"></div>
                            </td>
                        </tr>
                    </table>

                    <p class="saln-note mb-1 uppercase text-[9px] font-bold">Spouses, who are both public officials or employees, may file the SALN jointly or separately.</p>
                    <p class="saln-note mb-2 uppercase text-[9px] font-bold">The declarant shall check the appropriate box</p>
                    <div class="saln-checkbox-row mb-3">
                        <label class="saln-check-label"><input type="radio" name="filing_status" value="joint" class="saln-native-check" required><span class="saln-box"></span> Joint Filing</label>
                        <label class="saln-check-label"><input type="radio" name="filing_status" value="separate" class="saln-native-check"><span class="saln-box"></span> Separate Filing</label>
                        <label class="saln-check-label"><input type="radio" name="filing_status" value="not_applicable" class="saln-native-check"><span class="saln-box"></span> Not Applicable</label>
                    </div>

                    <p class="saln-note mb-2 uppercase text-[9px] font-bold">If with multiple marriages, indicate name(s) of spouses, otherwise check the "Not Applicable" box.</p>
                    <div class="mb-4 saln-multiple-marriage-section">
                        <div class="saln-multiple-marriage-row">
                            <input type="text" name="declarant_info[multiple_spouses][]" x-ref="spouse1" class="saln-input flex-1" placeholder="Name of spouse (if applicable)" :disabled="multipleMarriagesNA">
                            <label class="saln-check-label text-[10px]">
                                <input type="checkbox" name="declarant_info[multiple_marriages_not_applicable]" value="1" class="saln-native-check" x-model="multipleMarriagesNA" @change="if(multipleMarriagesNA) { $refs.spouse1.value = ''; $refs.spouse2.value = '' }">
                                <span class="saln-box"></span>
                                Not Applicable
                            </label>
                        </div>
                        <input type="text" name="declarant_info[multiple_spouses][]" x-ref="spouse2" class="saln-input w-full" placeholder="Name of spouse (if applicable)" :disabled="multipleMarriagesNA">
                    </div>

                    {{-- Children --}}
                    <div class="flex justify-between items-center mb-1">
                        <div class="saln-section-title mb-0 flex-1">Unmarried Children Below Eighteen (18) Years of Age Living in Declarant's Household</div>
                        <button type="button" @click="addChild()" class="print:hidden text-[10px] font-bold text-indigo-600 ml-2">+ Add</button>
                    </div>
                    <table class="mb-4">
                        <thead><tr><th class="saln-th w-2/3">NAME OF CHILD</th><th class="saln-th w-1/3">AGE</th></tr></thead>
                        <tbody>
                            <template x-for="(child, index) in children" :key="index">
                                <tr>
                                    <td><input type="text" :name="'children['+index+'][name]'" x-model="child.name" class="saln-cell-input text-center"></td>
                                    <td class="text-center">
                                        <input type="number" :name="'children['+index+'][age]'" x-model="child.age" class="saln-cell-input text-center w-16">
                                        <button type="button" @click="removeChild(index)" class="print:hidden text-red-500 text-[9px] ml-1">✕</button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="children.length === 0"><td colspan="2" class="text-center text-gray-400 italic text-[10px]">No children added</td></tr>
                        </tbody>
                    </table>

                    <div class="saln-section-title border-t-2 border-black pt-3">Assets, Liabilities and Networth</div>
                    <p class="saln-note text-center mb-3">(Including those of the spouse and unmarried children below eighteen (18) years of age living in declarant's household)</p>

                    <div class="flex justify-between items-center mb-1">
                        <p class="font-bold">1. ASSETS — a. Real Properties</p>
                        <button type="button" @click="addRealProperty()" class="print:hidden text-[10px] font-bold text-indigo-600">+ Add Row</button>
                    </div>
                    <table class="mb-1 text-[9px]">
                        <thead>
                            <tr>
                                <th class="saln-th" rowspan="2">DESCRIPTION</th>
                                <th class="saln-th" rowspan="2">KIND</th>
                                <th class="saln-th" rowspan="2">EXACT LOCATION</th>
                                <th class="saln-th" rowspan="2">ASSESSED VALUE</th>
                                <th class="saln-th" rowspan="2">CURRENT FAIR MARKET VALUE</th>
                                <th class="saln-th" colspan="2">ACQUISITION</th>
                                <th class="saln-th" rowspan="2">ACQUISITION COST</th>
                            </tr>
                            <tr><th class="saln-th">YEAR</th><th class="saln-th">MODE</th></tr>
                        </thead>
                        <tbody>
                            <template x-for="(prop, index) in realProperties" :key="index">
                                <tr>
                                    <td><input type="text" :name="'real_properties['+index+'][description]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'real_properties['+index+'][kind]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'real_properties['+index+'][location]'" class="saln-cell-input" required></td>
                                    <td><input type="number" step="0.01" :name="'real_properties['+index+'][assessed_value]'" class="saln-cell-input" required></td>
                                    <td><input type="number" step="0.01" :name="'real_properties['+index+'][fair_market_value]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'real_properties['+index+'][acquisition_year]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'real_properties['+index+'][acquisition_mode]'" class="saln-cell-input" required></td>
                                    <td>
                                        <input type="number" step="0.01" :name="'real_properties['+index+'][acquisition_cost]'" x-model.number="prop.acquisition_cost" class="saln-cell-input" required>
                                        <button type="button" @click="removeRealProperty(index)" class="print:hidden text-red-500 text-[9px]">✕</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center mb-1 mt-3">
                        <p class="font-bold ml-3">b. Personal Properties</p>
                        <button type="button" @click="addPersonalProperty()" class="print:hidden text-[10px] font-bold text-indigo-600">+ Add Row</button>
                    </div>
                    <table class="mb-4">
                        <thead><tr><th class="saln-th w-1/2">DESCRIPTION</th><th class="saln-th">ACQUISITION YEAR</th><th class="saln-th">ACQUISITION COST / AMOUNT</th></tr></thead>
                        <tbody>
                            <template x-for="(prop, index) in personalProperties" :key="index">
                                <tr>
                                    <td><input type="text" :name="'personal_properties['+index+'][description]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'personal_properties['+index+'][acquisition_year]'" class="saln-cell-input" required></td>
                                    <td>
                                        <input type="number" step="0.01" :name="'personal_properties['+index+'][acquisition_cost]'" x-model.number="prop.acquisition_cost" class="saln-cell-input" required>
                                        <button type="button" @click="removePersonalProperty(index)" class="print:hidden text-red-500 text-[9px]">✕</button>
                                    </td>
                                </tr>
                            </template>
                            <tr class="saln-total-row">
                                <td colspan="2" class="text-right font-bold">TOTAL ASSETS:</td>
                                <td class="text-center font-bold" x-text="'₱ ' + totalAssets.toLocaleString('en-PH', {minimumFractionDigits: 2})"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="grid grid-cols-2 gap-8 my-4 text-[10px] print:hidden">
                        <div class="text-center">
                            <div class="border-b border-black min-h-[28px] mb-1"></div>
                            <div class="font-bold">Signature/Initial of Declarant</div>
                        </div>
                        <div class="text-center">
                            <div class="border-b border-black min-h-[28px] mb-1"></div>
                            <div class="font-bold">Signature/Initial of Declarant</div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-1">
                        <div class="saln-section-title mb-0 flex-1">Liabilities</div>
                        <button type="button" @click="addLiability()" class="print:hidden text-[10px] font-bold text-indigo-600">+ Add Row</button>
                    </div>
                    <table class="mb-4">
                        <thead><tr><th class="saln-th w-1/3">NATURE</th><th class="saln-th w-1/3">NAME OF CREDITORS</th><th class="saln-th w-1/3">OUTSTANDING BALANCE</th></tr></thead>
                        <tbody>
                            <template x-for="(liab, index) in liabilities" :key="index">
                                <tr>
                                    <td><input type="text" :name="'liabilities['+index+'][nature]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'liabilities['+index+'][creditor]'" class="saln-cell-input" required></td>
                                    <td>
                                        <input type="number" step="0.01" :name="'liabilities['+index+'][outstanding_balance]'" x-model.number="liab.outstanding_balance" class="saln-cell-input" required>
                                        <button type="button" @click="removeLiability(index)" class="print:hidden text-red-500 text-[9px]">✕</button>
                                    </td>
                                </tr>
                            </template>
                            <tr class="saln-total-row">
                                <td colspan="2" class="text-right font-bold">TOTAL LIABILITIES:</td>
                                <td class="text-center font-bold" x-text="'₱ ' + totalLiabilities.toLocaleString('en-PH', {minimumFractionDigits: 2})"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="saln-networth mb-6">
                        <span>NET WORTH: Total Assets less Total Liabilities =</span>
                        <span x-text="'₱ ' + netWorth.toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                    </div>

                    {{-- Business --}}
                    <div class="saln-section-title border-t border-black pt-3">Business Interests and Financial Connections</div>
                    <p class="saln-note text-center mb-2">(of Declarant / Declarant's spouse / Unmarried Children Below Eighteen (18) years of Age Living in Declarant's Household)</p>
                    <label class="saln-check-label mb-2">
                        <input type="checkbox" name="no_business_interests" value="1" class="saln-native-check" x-model="noBusinessInterests" @change="if(noBusinessInterests) businessInterests = []">
                        <span class="saln-box"></span>
                        I/We do not have any business interest or financial connection.
                    </label>
                    <input type="hidden" name="has_business_interests" :value="noBusinessInterests ? 0 : 1">
                    <div class="flex justify-end mb-1" x-show="!noBusinessInterests">
                        <button type="button" @click="addBusinessInterest()" class="text-[10px] font-bold text-indigo-600">+ Add Row</button>
                    </div>
                    <table class="mb-4" x-show="!noBusinessInterests">
                        <thead>
                            <tr>
                                <th class="saln-th">NAME OF ENTITY/BUSINESS ENTERPRISE</th>
                                <th class="saln-th">BUSINESS ADDRESS</th>
                                <th class="saln-th">NATURE OF BUSINESS INTEREST &amp;/OR FINANCIAL CONNECTION</th>
                                <th class="saln-th">DATE OF ACQUISITION OF INTEREST OR CONNECTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(biz, index) in businessInterests" :key="index">
                                <tr>
                                    <td><input type="text" :name="'business_interests['+index+'][name]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'business_interests['+index+'][address]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'business_interests['+index+'][nature]'" class="saln-cell-input" required></td>
                                    <td><input type="date" :name="'business_interests['+index+'][acquisition_date]'" class="saln-cell-input" required></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    {{-- Relatives --}}
                    <div class="saln-section-title">Relatives in the Government Service</div>
                    <p class="saln-note text-center mb-2">(Within the Fourth Degree of Consanguinity or Affinity. Include also Bilas, Balae and Inso)</p>
                    <label class="saln-check-label mb-2">
                        <input type="checkbox" name="no_relatives_in_gov" value="1" class="saln-native-check" x-model="noRelativesInGov" @change="if(noRelativesInGov) relatives = []">
                        <span class="saln-box"></span>
                        I/We do not know of any relative/s in the government service
                    </label>
                    <input type="hidden" name="has_relatives_in_gov" :value="noRelativesInGov ? 0 : 1">
                    <div class="flex justify-end mb-1" x-show="!noRelativesInGov">
                        <button type="button" @click="addRelative()" class="text-[10px] font-bold text-indigo-600">+ Add Row</button>
                    </div>
                    <table class="mb-4" x-show="!noRelativesInGov">
                        <thead>
                            <tr>
                                <th class="saln-th">NAME OF RELATIVE</th>
                                <th class="saln-th">RELATIONSHIP</th>
                                <th class="saln-th">POSITION</th>
                                <th class="saln-th">NAME OF AGENCY/OFFICE AND ADDRESS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(rel, index) in relatives" :key="index">
                                <tr>
                                    <td><input type="text" :name="'relatives_in_gov['+index+'][name]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'relatives_in_gov['+index+'][relationship]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'relatives_in_gov['+index+'][position]'" class="saln-cell-input" required></td>
                                    <td><input type="text" :name="'relatives_in_gov['+index+'][agency]'" class="saln-cell-input" required></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <p class="saln-cert">
                        I hereby certify that these are true and correct statements of my assets, liabilities, net worth, business interests and financial connections, including those of my spouse and unmarried children below eighteen (18) years of age living in my household, and that to the best of my knowledge, the above-enumerated are names of my relatives in the government within the fourth civil degree of consanguinity or affinity.
                    </p>
                    <p class="saln-cert">
                        I hereby authorize the Ombudsman or his/her duly authorized representative to obtain and secure from all appropriate government agencies, including the Bureau of Internal Revenue such documents that may show my assets, liabilities, net worth, business interests and financial connections, to include those of my spouse and unmarried children below 18 years of age living with me in my household covering previous years to include the year I first assumed office in government.
                    </p>

                    <p class="text-[10px] mb-4">Date: ______________________________</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4 mb-6 text-[10px] print:hidden">
                        <div>
                            <div class="text-center border-b border-black pb-1 mb-1 min-h-[40px]"></div>
                            <div class="text-center font-bold">Signature of Declarant</div>
                            <div class="mt-3 space-y-1">
                                <div class="flex gap-2"><span class="font-bold w-32">Government Issued ID:</span><span class="saln-line flex-1"></span></div>
                                <div class="flex gap-2"><span class="font-bold w-32">ID No.:</span><span class="saln-line flex-1"></span></div>
                                <div class="flex gap-2"><span class="font-bold w-32">Date Issued:</span><span class="saln-line flex-1"></span></div>
                            </div>
                        </div>
                        <div>
                            <div class="text-center border-b border-black pb-1 mb-1 min-h-[40px]"></div>
                            <div class="text-center font-bold">Signature of Declarant</div>
                            <div class="mt-3 space-y-1">
                                <div class="flex gap-2"><span class="font-bold w-32">Government Issued ID:</span><span class="saln-line flex-1"></span></div>
                                <div class="flex gap-2"><span class="font-bold w-32">ID No.:</span><span class="saln-line flex-1"></span></div>
                                <div class="flex gap-2"><span class="font-bold w-32">Date Issued:</span><span class="saln-line flex-1"></span></div>
                            </div>
                        </div>
                    </div>

                    <p class="text-[10px] mb-8 print:hidden">
                        SUBSCRIBED AND SWORN to before me this _____ day of _____________, affiant exhibiting to me the above-stated government-issued identification card.
                    </p>
                    <div class="text-right text-[10px] mb-6 print:hidden">
                        <div class="inline-block border-b border-black min-w-[250px] mb-1">&nbsp;</div>
                        <div class="font-bold">(Person Administering Oath)</div>
                    </div>

                    <div class="pt-6 border-t flex justify-end gap-4 print:hidden">
                        <a href="{{ route('salns.index') }}" class="px-6 py-2 border border-gray-400 rounded text-sm font-bold">Cancel</a>
                        <button type="submit" class="px-8 py-2 bg-gray-800 text-white rounded text-sm font-bold hover:bg-gray-700">Submit SALN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function salnForm() {
            return {
                children: [],
                realProperties: [],
                personalProperties: [],
                liabilities: [],
                businessInterests: [],
                relatives: [],
                noBusinessInterests: true,
                noRelativesInGov: true,
                multipleMarriagesNA: false,
                filingType: '',
                assumptionDate: '',
                annualYear: '',
                exitDate: '',

                get asOfDate() {
                    if (this.filingType === 'assumption_of_office') return this.assumptionDate;
                    if (this.filingType === 'annual_filing') return this.annualYear ? `${this.annualYear}-12-31` : '';
                    if (this.filingType === 'exit') return this.exitDate;
                    return '';
                },

                addChild() { this.children.push({}); },
                removeChild(i) { this.children.splice(i, 1); },
                addRealProperty() { this.realProperties.push({ acquisition_cost: 0 }); },
                removeRealProperty(i) { this.realProperties.splice(i, 1); },
                addPersonalProperty() { this.personalProperties.push({ acquisition_cost: 0 }); },
                removePersonalProperty(i) { this.personalProperties.splice(i, 1); },
                addLiability() { this.liabilities.push({ outstanding_balance: 0 }); },
                removeLiability(i) { this.liabilities.splice(i, 1); },
                addBusinessInterest() { this.noBusinessInterests = false; this.businessInterests.push({}); },
                addRelative() { this.noRelativesInGov = false; this.relatives.push({}); },

                get totalAssets() {
                    const real = this.realProperties.reduce((s, p) => s + (parseFloat(p.acquisition_cost) || 0), 0);
                    const personal = this.personalProperties.reduce((s, p) => s + (parseFloat(p.acquisition_cost) || 0), 0);
                    return real + personal;
                },
                get totalLiabilities() {
                    return this.liabilities.reduce((s, l) => s + (parseFloat(l.outstanding_balance) || 0), 0);
                },
                get netWorth() {
                    return this.totalAssets - this.totalLiabilities;
                },
            };
        }
    </script>
</x-app-layout>
