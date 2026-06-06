<x-app-layout>
    <div class="py-12" x-data="salnForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 p-8">
                <div class="mb-8 text-center border-b pb-6">
                    <h1 class="text-2xl font-bold uppercase tracking-widest text-gray-900">Sworn Statement of Assets, Liabilities, and Net Worth</h1>
                    <p class="text-sm text-gray-500 mt-1">(As required by R.A. No. 6713)</p>
                </div>

                <form method="POST" action="{{ route('salns.store') }}" class="space-y-12">
                    @csrf

                    <!-- Compliance For -->
                    <section>
                        <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600 mb-4 border-b pb-2">Compliance For</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="type_of_filing" value="Filing Type" />
                                <select name="type_of_filing" class="mt-1 block w-full text-sm font-medium border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Select Filing Type</option>
                                    <option value="assumption_of_office">Assumption of office</option>
                                    <option value="annual_filing">Annual filing</option>
                                    <option value="exit">Exit</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="as_of_date" value="As of Date" />
                                <x-text-input type="date" name="as_of_date" class="mt-1 block w-full" required />
                            </div>
                        </div>
                    </section>

                    <!-- Declarant & Spouse -->
                    <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600 mb-4 border-b pb-2">Declarant</h3>
                            <div class="space-y-4">
                                <div><x-input-label value="Family Name" /><x-text-input name="declarant_info[family_name]" class="mt-1 block w-full" required /></div>
                                <div><x-input-label value="First Name" /><x-text-input name="declarant_info[first_name]" class="mt-1 block w-full" required /></div>
                                <div><x-input-label value="Middle Initial" /><x-text-input name="declarant_info[middle_initial]" class="mt-1 block w-full" /></div>
                                <div><x-input-label value="Position" /><x-text-input name="declarant_info[position]" class="mt-1 block w-full" required /></div>
                                <div><x-input-label value="Agency/Office" /><x-text-input name="declarant_info[agency]" class="mt-1 block w-full" required /></div>
                                <div><x-input-label value="Office Address" /><x-text-input name="declarant_info[office_address]" class="mt-1 block w-full" required /></div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600 mb-4 border-b pb-2">Spouse</h3>
                            <div class="space-y-4">
                                <div><x-input-label value="Family Name" /><x-text-input name="spouse_info[family_name]" class="mt-1 block w-full" /></div>
                                <div><x-input-label value="First Name" /><x-text-input name="spouse_info[first_name]" class="mt-1 block w-full" /></div>
                                <div><x-input-label value="Middle Initial" /><x-text-input name="spouse_info[middle_initial]" class="mt-1 block w-full" /></div>
                                <div><x-input-label value="Position" /><x-text-input name="spouse_info[position]" class="mt-1 block w-full" /></div>
                                <div><x-input-label value="Agency/Office" /><x-text-input name="spouse_info[agency]" class="mt-1 block w-full" /></div>
                                <div><x-input-label value="Office Address" /><x-text-input name="spouse_info[office_address]" class="mt-1 block w-full" /></div>
                            </div>
                        </div>
                    </section>

                    <!-- Filing Status -->
                    <section>
                        <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600 mb-4 border-b pb-2">Filing Status</h3>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2"><input type="radio" name="filing_status" value="joint" class="text-indigo-600" required> Joint Filing</label>
                            <label class="flex items-center gap-2"><input type="radio" name="filing_status" value="separate" class="text-indigo-600" required> Separate Filing</label>
                            <label class="flex items-center gap-2"><input type="radio" name="filing_status" value="not_applicable" class="text-indigo-600" required> Not Applicable</label>
                        </div>
                    </section>

                    <!-- Unmarried Children -->
                    <section>
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600">Unmarried Children Below 18 Years of Age</h3>
                            <button type="button" @click="addChild" class="text-xs font-bold bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">+ Add Child</button>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(child, index) in children" :key="index">
                                <div class="flex gap-4 items-center">
                                    <div class="flex-1"><x-input-label value="Name of Child" /><input type="text" x-model="child.name" :name="'children['+index+'][name]'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div class="w-24"><x-input-label value="Age" /><input type="number" x-model="child.age" :name="'children['+index+'][age]'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <button type="button" @click="removeChild(index)" class="mt-6 text-red-500 hover:text-red-700">Remove</button>
                                </div>
                            </template>
                            <p x-show="children.length === 0" class="text-sm text-gray-500 italic">No children added.</p>
                        </div>
                    </section>

                    <!-- Assets: Real Properties -->
                    <section>
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600">Assets: Real Properties</h3>
                            <button type="button" @click="addRealProperty" class="text-xs font-bold bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">+ Add Real Property</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                                    <tr>
                                        <th class="p-2 border">Description</th>
                                        <th class="p-2 border">Kind</th>
                                        <th class="p-2 border">Exact Location</th>
                                        <th class="p-2 border">Assessed Value</th>
                                        <th class="p-2 border">Current Fair Market Value</th>
                                        <th class="p-2 border">Acquisition Year</th>
                                        <th class="p-2 border">Acquisition Mode</th>
                                        <th class="p-2 border">Acquisition Cost</th>
                                        <th class="p-2 border"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(prop, index) in realProperties" :key="index">
                                        <tr>
                                            <td class="p-1 border"><input type="text" :name="'real_properties['+index+'][description]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'real_properties['+index+'][kind]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'real_properties['+index+'][location]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="number" step="0.01" :name="'real_properties['+index+'][assessed_value]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="number" step="0.01" :name="'real_properties['+index+'][fair_market_value]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'real_properties['+index+'][acquisition_year]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'real_properties['+index+'][acquisition_mode]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="number" step="0.01" :name="'real_properties['+index+'][acquisition_cost]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border text-center"><button type="button" @click="removeRealProperty(index)" class="text-red-500 hover:text-red-700 text-xs font-bold">✕</button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <p x-show="realProperties.length === 0" class="text-sm text-gray-500 italic mt-2">No real properties added.</p>
                        </div>
                    </section>

                    <!-- Assets: Personal Properties -->
                    <section>
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600">Assets: Personal Properties</h3>
                            <button type="button" @click="addPersonalProperty" class="text-xs font-bold bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">+ Add Personal Property</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                                    <tr>
                                        <th class="p-2 border w-1/2">Description</th>
                                        <th class="p-2 border">Acquisition Year</th>
                                        <th class="p-2 border">Acquisition Cost / Amount</th>
                                        <th class="p-2 border w-10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(prop, index) in personalProperties" :key="index">
                                        <tr>
                                            <td class="p-1 border"><input type="text" :name="'personal_properties['+index+'][description]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'personal_properties['+index+'][acquisition_year]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="number" step="0.01" :name="'personal_properties['+index+'][acquisition_cost]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border text-center"><button type="button" @click="removePersonalProperty(index)" class="text-red-500 hover:text-red-700 text-xs font-bold">✕</button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <p x-show="personalProperties.length === 0" class="text-sm text-gray-500 italic mt-2">No personal properties added.</p>
                        </div>
                    </section>

                    <!-- Liabilities -->
                    <section>
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600">Liabilities</h3>
                            <button type="button" @click="addLiability" class="text-xs font-bold bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">+ Add Liability</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                                    <tr>
                                        <th class="p-2 border">Nature</th>
                                        <th class="p-2 border">Name of Creditors</th>
                                        <th class="p-2 border">Outstanding Balance</th>
                                        <th class="p-2 border w-10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(liab, index) in liabilities" :key="index">
                                        <tr>
                                            <td class="p-1 border"><input type="text" :name="'liabilities['+index+'][nature]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'liabilities['+index+'][creditor]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="number" step="0.01" :name="'liabilities['+index+'][outstanding_balance]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border text-center"><button type="button" @click="removeLiability(index)" class="text-red-500 hover:text-red-700 text-xs font-bold">✕</button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <p x-show="liabilities.length === 0" class="text-sm text-gray-500 italic mt-2">No liabilities added.</p>
                        </div>
                    </section>

                    <!-- Business Interests -->
                    <section>
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600">Business Interests and Financial Connections</h3>
                            <button type="button" @click="addBusinessInterest" class="text-xs font-bold bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">+ Add Business Interest</button>
                        </div>
                        <div class="mb-3">
                            <label class="flex items-center gap-2"><input type="checkbox" name="has_business_interests" value="1" class="text-indigo-600" :checked="businessInterests.length > 0"> I/We have business interests</label>
                            <input type="hidden" name="has_business_interests" value="0" x-bind:disabled="businessInterests.length > 0">
                        </div>
                        <div class="overflow-x-auto" x-show="businessInterests.length > 0">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                                    <tr>
                                        <th class="p-2 border">Name of Entity / Business Enterprise</th>
                                        <th class="p-2 border">Business Address</th>
                                        <th class="p-2 border">Nature of Business Interest</th>
                                        <th class="p-2 border">Date of Acquisition</th>
                                        <th class="p-2 border w-10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(biz, index) in businessInterests" :key="index">
                                        <tr>
                                            <td class="p-1 border"><input type="text" :name="'business_interests['+index+'][name]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'business_interests['+index+'][address]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'business_interests['+index+'][nature]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="date" :name="'business_interests['+index+'][acquisition_date]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border text-center"><button type="button" @click="removeBusinessInterest(index)" class="text-red-500 hover:text-red-700 text-xs font-bold">✕</button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Relatives in Government -->
                    <section>
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600">Relatives in the Government Service</h3>
                            <button type="button" @click="addRelative" class="text-xs font-bold bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">+ Add Relative</button>
                        </div>
                        <div class="mb-3">
                            <label class="flex items-center gap-2"><input type="checkbox" name="has_relatives_in_gov" value="1" class="text-indigo-600" :checked="relatives.length > 0"> I/We have relatives in government</label>
                            <input type="hidden" name="has_relatives_in_gov" value="0" x-bind:disabled="relatives.length > 0">
                        </div>
                        <div class="overflow-x-auto" x-show="relatives.length > 0">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                                    <tr>
                                        <th class="p-2 border">Name of Relative</th>
                                        <th class="p-2 border">Relationship</th>
                                        <th class="p-2 border">Position</th>
                                        <th class="p-2 border">Name of Agency/Office and Address</th>
                                        <th class="p-2 border w-10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(rel, index) in relatives" :key="index">
                                        <tr>
                                            <td class="p-1 border"><input type="text" :name="'relatives_in_gov['+index+'][name]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'relatives_in_gov['+index+'][relationship]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'relatives_in_gov['+index+'][position]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border"><input type="text" :name="'relatives_in_gov['+index+'][agency]'" class="w-full text-sm border-gray-300 rounded" required></td>
                                            <td class="p-1 border text-center"><button type="button" @click="removeRelative(index)" class="text-red-500 hover:text-red-700 text-xs font-bold">✕</button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <div class="pt-8 border-t flex justify-end gap-4">
                        <a href="{{ route('salns.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-bold uppercase text-sm">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-bold uppercase text-sm shadow-lg shadow-indigo-200">Submit SALN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function salnForm() {
            return {
                children: [],
                addChild() { this.children.push({}); },
                removeChild(index) { this.children.splice(index, 1); },

                realProperties: [],
                addRealProperty() { this.realProperties.push({}); },
                removeRealProperty(index) { this.realProperties.splice(index, 1); },

                personalProperties: [],
                addPersonalProperty() { this.personalProperties.push({}); },
                removePersonalProperty(index) { this.personalProperties.splice(index, 1); },

                liabilities: [],
                addLiability() { this.liabilities.push({}); },
                removeLiability(index) { this.liabilities.splice(index, 1); },

                businessInterests: [],
                addBusinessInterest() { this.businessInterests.push({}); },
                removeBusinessInterest(index) { this.businessInterests.splice(index, 1); },

                relatives: [],
                addRelative() { this.relatives.push({}); },
                removeRelative(index) { this.relatives.splice(index, 1); }
            }
        }
    </script>
</x-app-layout>
