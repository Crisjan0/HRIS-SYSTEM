                @include('saln.partials.official-meta')

                <p class="font-bold text-[11px] uppercase mb-2">Compliance For:</p>
                <div class="saln-compliance-row mb-4 border-b border-black pb-3">
                    <span class="saln-compliance-option">
                        @include('saln.partials.box', ['checked' => $saln->type_of_filing === 'assumption_of_office'])
                        Assumption of office as of {{ $saln->type_of_filing === 'assumption_of_office' ? $saln->as_of_date->format('F d, Y') : '_______________' }}
                    </span>
                    <span class="saln-compliance-option">
                        @include('saln.partials.box', ['checked' => $saln->type_of_filing === 'annual_filing'])
                        Annual filing as of December 31, {{ $saln->type_of_filing === 'annual_filing' ? $saln->as_of_date->format('Y') : '________' }}
                    </span>
                    <span class="saln-compliance-option">
                        @include('saln.partials.box', ['checked' => $saln->type_of_filing === 'exit'])
                        Exit as of {{ $saln->type_of_filing === 'exit' ? $saln->as_of_date->format('F d, Y') : '_______________' }}
                    </span>
                </div>

                {{-- Declarant & Spouse --}}
                <table class="mb-2">
                    <tr>
                        <td class="w-[12%] font-bold align-top">DECLARANT:</td>
                        <td class="w-[22%] text-center">{{ $saln->declarant_info['family_name'] ?? '' }}<br><span class="text-[8px]">(Family Name)</span></td>
                        <td class="w-[22%] text-center">{{ $saln->declarant_info['first_name'] ?? '' }}<br><span class="text-[8px]">(First Name)</span></td>
                        <td class="w-[8%] text-center">{{ $saln->declarant_info['middle_initial'] ?? '' }}<br><span class="text-[8px]">(M.I.)</span></td>
                        <td class="w-[36%] align-top">
                            <div class="flex mb-1"><span class="w-28 font-bold shrink-0">POSITION:</span><span>{{ $saln->declarant_info['position'] ?? '' }}</span></div>
                            <div class="flex mb-1"><span class="w-28 font-bold shrink-0">AGENCY/OFFICE:</span><span>{{ $saln->declarant_info['agency'] ?? $saln->declarant_info['agency_office'] ?? '' }}</span></div>
                            <div class="flex"><span class="w-28 font-bold shrink-0">OFFICE ADDRESS:</span><span>{{ $saln->declarant_info['office_address'] ?? '' }}</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold align-top">SPOUSE:</td>
                        <td class="text-center">{{ $saln->spouse_info['family_name'] ?? '' }}<br><span class="text-[8px]">(Family Name)</span></td>
                        <td class="text-center">{{ $saln->spouse_info['first_name'] ?? '' }}<br><span class="text-[8px]">(First Name)</span></td>
                        <td class="text-center">{{ $saln->spouse_info['middle_initial'] ?? '' }}<br><span class="text-[8px]">(M.I.)</span></td>
                        <td class="align-top">
                            <div class="flex mb-1"><span class="w-28 font-bold shrink-0">POSITION:</span><span>{{ $saln->spouse_info['position'] ?? '' }}</span></div>
                            <div class="flex mb-1"><span class="w-28 font-bold shrink-0">AGENCY/OFFICE:</span><span>{{ $saln->spouse_info['agency'] ?? $saln->spouse_info['agency_office'] ?? '' }}</span></div>
                            <div class="flex"><span class="w-28 font-bold shrink-0">OFFICE ADDRESS:</span><span>{{ $saln->spouse_info['office_address'] ?? '' }}</span></div>
                        </td>
                    </tr>
                </table>

                @php
                    $multipleSpouses = $saln->declarant_info['multiple_spouses'] ?? [];
                    $multipleMarriagesNA = ($saln->declarant_info['multiple_marriages_not_applicable'] ?? null) !== false && empty(array_filter($multipleSpouses));
                @endphp
                <p class="saln-note mb-1 uppercase text-[9px] font-bold">Spouses, who are both public officials or employees, may file the SALN jointly or separately.</p>
                <p class="saln-note mb-2 uppercase text-[9px] font-bold">The declarant shall check the appropriate box</p>
                <div class="saln-checkbox-row mb-3">
                    <span class="saln-check-label">@include('saln.partials.box', ['checked' => strtolower($saln->filing_status) === 'joint' || $saln->filing_status === 'Joint Filing']) Joint Filing</span>
                    <span class="saln-check-label">@include('saln.partials.box', ['checked' => strtolower($saln->filing_status) === 'separate' || $saln->filing_status === 'Separate Filing']) Separate Filing</span>
                    <span class="saln-check-label">@include('saln.partials.box', ['checked' => strtolower($saln->filing_status) === 'not_applicable' || $saln->filing_status === 'Not Applicable']) Not Applicable</span>
                </div>

                <p class="saln-note mb-2 uppercase text-[9px] font-bold">If with multiple marriages, indicate name(s) of spouses, otherwise check the "Not Applicable" box.</p>
                <div class="mb-4 saln-multiple-marriage-section">
                    @if($multipleMarriagesNA)
                        <div class="saln-multiple-marriage-row">
                            <span class="saln-line flex-1">&nbsp;</span>
                            <span class="saln-check-label mt-1">@include('saln.partials.box', ['checked' => true]) Not Applicable</span>
                        </div>
                        <div class="saln-line">&nbsp;</div>
                    @else
                        @foreach($multipleSpouses as $spouseName)
                            <div class="saln-line">{{ $spouseName }}</div>
                        @endforeach
                        @for($i = count($multipleSpouses); $i < 2; $i++)
                            <div class="saln-line">&nbsp;</div>
                        @endfor
                    @endif
                </div>

                {{-- Children --}}
                <div class="saln-section-title">Unmarried Children Below Eighteen (18) Years of Age Living in Declarant's Household</div>
                <table class="mb-4">
                    <thead>
                        <tr>
                            <th class="saln-th w-2/3">NAME OF CHILD</th>
                            <th class="saln-th w-1/3">AGE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saln->children ?? [] as $child)
                            <tr><td class="text-center">{{ $child['name'] ?? '' }}</td><td class="text-center">{{ $child['age'] ?? '' }}</td></tr>
                        @empty
                            @for($i = 0; $i < 4; $i++)
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>

                {{-- Assets header --}}
                <div class="saln-section-title border-t-2 border-black pt-3">Assets, Liabilities and Networth</div>
                <p class="saln-note text-center mb-3">(Including those of the spouse and unmarried children below eighteen (18) years of age living in declarant's household)</p>

                <p class="font-bold mb-1">1. ASSETS</p>
                <p class="font-bold mb-1 ml-3">a. Real Properties</p>
                <table class="mb-1 text-[9px]">
                    <thead>
                        <tr>
                            <th class="saln-th" rowspan="2">DESCRIPTION<br><span class="saln-th-sm">(e.g. Lot, House and Lot, Condominium and Improvements)</span></th>
                            <th class="saln-th" rowspan="2">KIND<br><span class="saln-th-sm">(e.g. Residential, Commercial, Industrial, Agricultural and Mixed Use)</span></th>
                            <th class="saln-th" rowspan="2">EXACT<br>LOCATION</th>
                            <th class="saln-th" rowspan="2">ASSESSED VALUE<br><span class="saln-th-sm">(As found in the Tax Declaration of Real Property)</span></th>
                            <th class="saln-th" rowspan="2">CURRENT FAIR MARKET VALUE<br><span class="saln-th-sm">(As found in the Tax Declaration of Real Property)</span></th>
                            <th class="saln-th" colspan="2">ACQUISITION</th>
                            <th class="saln-th" rowspan="2">ACQUISITION COST</th>
                        </tr>
                        <tr>
                            <th class="saln-th">YEAR</th>
                            <th class="saln-th">MODE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $realSubtotal = 0; @endphp
                        @forelse($saln->real_properties ?? [] as $prop)
                            @php $realSubtotal += (float)($prop['acquisition_cost'] ?? 0); @endphp
                            <tr class="text-center">
                                <td>{{ $prop['description'] ?? '' }}</td>
                                <td>{{ $prop['kind'] ?? '' }}</td>
                                <td>{{ $prop['location'] ?? $prop['exact_location'] ?? '' }}</td>
                                <td>{{ isset($prop['assessed_value']) ? '₱'.number_format((float)$prop['assessed_value'], 2) : '' }}</td>
                                <td>{{ isset($prop['fair_market_value']) || isset($prop['current_fair_market_value']) ? '₱'.number_format((float)($prop['fair_market_value'] ?? $prop['current_fair_market_value'] ?? 0), 2) : '' }}</td>
                                <td>{{ $prop['acquisition_year'] ?? '' }}</td>
                                <td>{{ $prop['acquisition_mode'] ?? '' }}</td>
                                <td class="font-bold">{{ isset($prop['acquisition_cost']) ? '₱'.number_format((float)$prop['acquisition_cost'], 2) : '' }}</td>
                            </tr>
                        @empty
                            @for($i = 0; $i < 5; $i++)
                                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            @endfor
                        @endforelse
                        <tr class="saln-total-row">
                            <td colspan="7" class="text-right font-bold">Subtotal:</td>
                            <td class="text-center font-bold">₱{{ number_format($realSubtotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <p class="font-bold mb-1 ml-3 mt-3">b. Personal Properties</p>
                <table class="mb-1">
                    <thead>
                        <tr>
                            <th class="saln-th w-1/2">DESCRIPTION</th>
                            <th class="saln-th">ACQUISITION YEAR</th>
                            <th class="saln-th">ACQUISITION COST / AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $personalSubtotal = 0; @endphp
                        @forelse($saln->personal_properties ?? [] as $prop)
                            @php $personalSubtotal += (float)($prop['acquisition_cost'] ?? 0); @endphp
                            <tr class="text-center">
                                <td>{{ $prop['description'] ?? '' }}</td>
                                <td>{{ $prop['acquisition_year'] ?? $prop['year_acquired'] ?? '' }}</td>
                                <td class="font-bold">{{ isset($prop['acquisition_cost']) ? '₱'.number_format((float)$prop['acquisition_cost'], 2) : '' }}</td>
                            </tr>
                        @empty
                            @for($i = 0; $i < 6; $i++)
                                <tr><td>&nbsp;</td><td></td><td></td></tr>
                            @endfor
                        @endforelse
                        <tr class="saln-total-row">
                            <td colspan="2" class="text-right font-bold">Subtotal:</td>
                            <td class="text-center font-bold">₱{{ number_format($personalSubtotal, 2) }}</td>
                        </tr>
                        <tr class="saln-total-row">
                            <td colspan="2" class="text-right font-bold text-[11px]">TOTAL ASSETS:</td>
                            <td class="text-center font-bold text-[11px]">₱{{ number_format($saln->total_assets, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="grid grid-cols-2 gap-8 my-4 text-[10px]">
                    <div class="text-center">
                        <div class="border-b border-black min-h-[28px] mb-1"></div>
                        <div class="font-bold">Signature/Initial of Declarant</div>
                    </div>
                    <div class="text-center">
                        <div class="border-b border-black min-h-[28px] mb-1"></div>
                        <div class="font-bold">Signature/Initial of Declarant</div>
                    </div>
                </div>

                <div class="saln-section-title mb-2">Liabilities</div>
                <table class="mb-4">
                    <thead>
                        <tr>
                            <th class="saln-th w-1/3">NATURE</th>
                            <th class="saln-th w-1/3">NAME OF CREDITORS</th>
                            <th class="saln-th w-1/3">OUTSTANDING BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saln->liabilities ?? [] as $liab)
                            <tr class="text-center">
                                <td>{{ $liab['nature'] ?? '' }}</td>
                                <td>{{ $liab['creditor'] ?? $liab['name_of_creditors'] ?? '' }}</td>
                                <td class="font-bold">{{ isset($liab['outstanding_balance']) ? '₱'.number_format((float)$liab['outstanding_balance'], 2) : '' }}</td>
                            </tr>
                        @empty
                            @for($i = 0; $i < 3; $i++)
                                <tr><td>&nbsp;</td><td></td><td></td></tr>
                            @endfor
                        @endforelse
                        <tr class="saln-total-row">
                            <td colspan="2" class="text-right font-bold">TOTAL LIABILITIES:</td>
                            <td class="text-center font-bold">₱{{ number_format($saln->total_liabilities, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="saln-networth mb-6">
                    <span>NET WORTH: Total Assets less Total Liabilities =</span>
                    <span class="text-[14px]">₱{{ number_format($saln->net_worth, 2) }}</span>
                </div>

                {{-- Business Interests --}}
                <div class="saln-section-title border-t border-black pt-3">Business Interests and Financial Connections</div>
                <p class="saln-note text-center mb-2">(of Declarant / Declarant's spouse / Unmarried Children Below Eighteen (18) years of Age Living in Declarant's Household)</p>
                <p class="mb-2">
                    <span class="saln-check-label">
                        @include('saln.partials.box', ['checked' => !$saln->has_business_interests])
                        I/We do not have any business interest or financial connection.
                    </span>
                </p>
                <table class="mb-4">
                    <thead>
                        <tr>
                            <th class="saln-th">NAME OF ENTITY/BUSINESS ENTERPRISE</th>
                            <th class="saln-th">BUSINESS ADDRESS</th>
                            <th class="saln-th">NATURE OF BUSINESS INTEREST &amp;/OR FINANCIAL CONNECTION</th>
                            <th class="saln-th">DATE OF ACQUISITION OF INTEREST OR CONNECTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saln->business_interests ?? [] as $biz)
                            <tr class="text-center text-[10px]">
                                <td>{{ $biz['name'] ?? '' }}</td>
                                <td>{{ $biz['address'] ?? '' }}</td>
                                <td>{{ $biz['nature'] ?? '' }}</td>
                                <td>{{ isset($biz['acquisition_date']) ? \Carbon\Carbon::parse($biz['acquisition_date'])->format('M d, Y') : '' }}</td>
                            </tr>
                        @empty
                            @for($i = 0; $i < 3; $i++)
                                <tr><td>&nbsp;</td><td></td><td></td><td></td></tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>

                {{-- Relatives --}}
                <div class="saln-section-title">Relatives in the Government Service</div>
                <p class="saln-note text-center mb-2">(Within the Fourth Degree of Consanguinity or Affinity. Include also Bilas, Balae and Inso)</p>
                <p class="mb-2">
                    <span class="saln-check-label">
                        @include('saln.partials.box', ['checked' => !$saln->has_relatives_in_gov])
                        I/We do not know of any relative/s in the government service
                    </span>
                </p>
                <table class="mb-4">
                    <thead>
                        <tr>
                            <th class="saln-th">NAME OF RELATIVE</th>
                            <th class="saln-th">RELATIONSHIP</th>
                            <th class="saln-th">POSITION</th>
                            <th class="saln-th">NAME OF AGENCY/OFFICE AND ADDRESS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saln->relatives_in_gov ?? [] as $rel)
                            <tr class="text-center text-[10px]">
                                <td>{{ $rel['name'] ?? '' }}</td>
                                <td>{{ $rel['relationship'] ?? '' }}</td>
                                <td>{{ $rel['position'] ?? '' }}</td>
                                <td>{{ $rel['agency'] ?? '' }}</td>
                            </tr>
                        @empty
                            @for($i = 0; $i < 4; $i++)
                                <tr><td>&nbsp;</td><td></td><td></td><td></td></tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>

                {{-- Certification --}}
                <p class="saln-cert">
                    I hereby certify that these are true and correct statements of my assets, liabilities, net worth, business interests and financial connections, including those of my spouse and unmarried children below eighteen (18) years of age living in my household, and that to the best of my knowledge, the above-enumerated are names of my relatives in the government within the fourth civil degree of consanguinity or affinity.
                </p>
                <p class="saln-cert">
                    I hereby authorize the Ombudsman or his/her duly authorized representative to obtain and secure from all appropriate government agencies, including the Bureau of Internal Revenue such documents that may show my assets, liabilities, net worth, business interests and financial connections, to include those of my spouse and unmarried children below 18 years of age living with me in my household covering previous years to include the year I first assumed office in government.
                </p>

                <p class="text-[10px] mb-4">Date: ______________________________</p>

                {{-- Signatures --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4 mb-6 text-[10px]">
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

                <p class="text-[10px] mb-8">
                    SUBSCRIBED AND SWORN to before me this _____ day of _____________, affiant exhibiting to me the above-stated government-issued identification card.
                </p>
                <div class="text-right text-[10px]">
                    <div class="inline-block border-b border-black min-w-[250px] mb-1">&nbsp;</div>
                    <div class="font-bold">(Person Administering Oath)</div>
                </div>
