<x-app-layout>
    <x-slot name="title">{{ __('Update Personal Data Sheet (CS Form No. 212)') }}</x-slot>

    <style>
        /* Always-visible field background only */
        .pds-form-body input:not([type="radio"]):not([type="checkbox"]):not([type="hidden"]):not([type="file"]),
        .pds-form-body select,
        .pds-form-body textarea {
            background-color: #eef2ff !important;
        }
    </style>


    @php
        $allowedTabs = ['personal', 'family', 'education', 'eligibility', 'work', 'voluntary', 'training', 'otherinfo', 'declaration'];
        $initialTab = in_array(request('tab'), $allowedTabs, true) ? request('tab') : 'personal';
    @endphp

    <div class="pds-line-page py-8 bg-white min-h-screen" x-data="{ 
        tab: @js($initialTab),
        children: {{ $employee->pdsChildren->count() > 0 ? $employee->pdsChildren->toJson() : '[{fullname: \'\', date_of_birth: \'\'}]' }},
        education: {{ $employee->pdsEducation->count() > 0 ? $employee->pdsEducation->toJson() : '[{level: \'\', school_name: \'\', course: \'\', period_from: \'\', period_to: \'\', highest_level: \'\', year_graduated: \'\', honors: \'\'}]' }},
        eligibility: {{ $employee->pdsEligibilities->count() > 0 ? $employee->pdsEligibilities->toJson() : '[{title: \'\', rating: \'\', date_of_exam: \'\', place_of_exam: \'\', license_number: \'\', license_validity: \'\'}]' }},
        work: {{ $employee->pdsWorkExperiences->count() > 0 ? $employee->pdsWorkExperiences->toJson() : '[{date_from: \'\', date_to: \'\', position_title: \'\', company: \'\', monthly_salary: \'\', salary_grade: \'\', appointment_status: \'\', is_gov_service: 0}]' }},
        training: {{ $employee->pdsTrainings->count() > 0 ? $employee->pdsTrainings->toJson() : '[{title: \'\', date_from: \'\', date_to: \'\', number_of_hours: \'\', attachment_path: \'\', type: \'\', conducted_by: \'\'}]' }},
        voluntary: {{ $employee->pdsVoluntaryWorks->count() > 0 ? $employee->pdsVoluntaryWorks->toJson() : '[{organization_name: \'\', date_from: \'\', date_to: \'\', number_of_hours: \'\', position: \'\'}]' }},
        others: {{ $employee->pdsOthers->count() > 0 ? $employee->pdsOthers->toJson() : '[{type: \'Skill\', description: \'\'}]' }},
        references: {{ $employee->pdsReferences->count() > 0 ? $employee->pdsReferences->toJson() : '[{name: \'\', address: \'\', telephone_no: \'\'}, {name: \'\', address: \'\', telephone_no: \'\'}, {name: \'\', address: \'\', telephone_no: \'\'}]' }},
        signaturePreview: @js($employee->effective_signature_url),
        photoPreview: @js($employee->profile_picture_url)
    }">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between print:hidden">
                <a href="{{ route('pds.index') }}" class="inline-flex w-fit items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('pds.download') }}" data-no-transition class="rounded-lg bg-white px-4 py-2 text-xs font-black uppercase tracking-widest text-indigo-700 ring-1 ring-indigo-100 hover:bg-indigo-50">Download PDS</a>
                    <a href="{{ route('pds.print') }}" data-no-transition target="_blank" class="rounded-lg bg-indigo-700 px-4 py-2 text-xs font-black uppercase tracking-widest text-white hover:bg-indigo-800">Preview PDS</a>
                </div>
            </div>

            <div class="mb-4 print:hidden">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Personal Data Sheet</h2>
                    <p class="mt-1 text-sm font-medium text-slate-600">Please put N/A for fields if not applicable.</p>
                </div>
            </div>

            <div class="pds-paper overflow-hidden rounded border border-slate-200 bg-white shadow-sm">
                <div class="pds-official-header hidden border-b border-slate-200 px-6 py-5 text-center">
                    <p class="text-[10px] font-bold">CS Form No. 212</p>
                    <h1 class="text-2xl font-black tracking-widest text-black">PERSONAL DATA SHEET</h1>
                    <p class="text-[10px] font-bold">Revised 2017</p>
                    <p class="mx-auto mt-3 max-w-3xl text-[10px] font-bold leading-relaxed text-left">
                        WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned. Print legibly. Tick appropriate boxes and indicate N/A if not applicable.
                    </p>
                </div>
                
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 bg-white print:hidden">
                    <nav class="flex items-center gap-5 overflow-x-auto px-6 pt-5" aria-label="PDS sections">
                        <button @click="tab = 'personal'" :class="tab === 'personal' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            I. Personal Information
                        </button>
                        <button @click="tab = 'family'" :class="tab === 'family' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            II. Family Background
                        </button>
                        <button @click="tab = 'education'" :class="tab === 'education' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            III. Education
                        </button>
                        <button @click="tab = 'eligibility'" :class="tab === 'eligibility' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            IV. Eligibility
                        </button>
                        <button @click="tab = 'work'" :class="tab === 'work' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            V. Work Experience
                        </button>
                        <button @click="tab = 'voluntary'" :class="tab === 'voluntary' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            VI. Voluntary Work
                        </button>
                        <button @click="tab = 'training'" :class="tab === 'training' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            VII. L&D
                        </button>
                        <button @click="tab = 'otherinfo'" :class="tab === 'otherinfo' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            VIII. Other Information
                        </button>
                        <button @click="tab = 'declaration'" :class="tab === 'declaration' ? 'border-indigo-700 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800'" class="shrink-0 whitespace-nowrap border-b-2 px-1 pb-3 text-xs font-black uppercase tracking-[0.16em] transition-all duration-200">
                            IX. Declaration
                        </button>
                    </nav>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="p-6 bg-red-50 border-b-2 border-red-100">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-sm font-black text-red-800 uppercase tracking-widest">Saving Failed</h3>
                        </div>
                        <ul class="list-disc list-inside text-xs font-bold text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pds.update') }}" method="POST" class="pds-form-body p-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Section I: Personal Information -->
                    <div x-show="tab === 'personal'" x-cloak class="space-y-10 animate-fade-in">
                        <!-- SECTION I: PERSONAL INFORMATION -->
                        <div>
                            <div class="pds-section-heading flex justify-between items-center border border-black bg-gray-200 px-3 py-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Personal Information</h3>
                                </div>
                                @php $personalReview = $employee->pdsSectionReviews->where('section_name', 'Personal Information')->first(); @endphp
                                @if($personalReview && $personalReview->remarks)
                                    <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-1 rounded font-bold border border-yellow-200">Has HR Remarks</span>
                                @endif
                            </div>
                            @if($personalReview && $personalReview->remarks)
                                <div class="mb-6 bg-yellow-50/50 border border-yellow-200 p-4 rounded-xl flex gap-3 items-start shadow-sm">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <div>
                                        <span class="block text-[10px] font-black text-yellow-800 uppercase tracking-widest mb-1">HR Remarks ({{ ucfirst($personalReview->status) }})</span>
                                        <p class="text-sm font-medium text-yellow-900 leading-relaxed">{{ $personalReview->remarks }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="space-y-8">
                                <div class="pds-field-table pds-field-table-4">
                                    <!-- 1. Surname -->
                                    <div class="space-y-1">
                                        <x-input-label value="Surname" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                        <x-text-input class="block w-full text-sm font-bold border-gray-100 bg-gray-50/30" name="personal[surname]" :value="old('personal.surname', $employee->pdsPersonal?->surname ?? $employee->lastname)" />
                                    </div>
                                    <!-- 2. First Name & Extension -->
                                    <div class="contents">
                                        <div class="space-y-1">
                                            <x-input-label value="First Name" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <x-text-input class="block w-full text-sm font-bold border-gray-100 bg-gray-50/30" name="personal[firstname]" :value="old('personal.firstname', $employee->pdsPersonal?->firstname ?? $employee->firstname)" />
                                        </div>
                                        <div class="space-y-1">
                                            <x-input-label value="Middle Name" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <x-text-input class="block w-full text-sm font-bold border-gray-100 bg-gray-50/30" name="personal[middlename]" :value="old('personal.middlename', $employee->pdsPersonal?->middlename ?? $employee->middlename)" />
                                        </div>
                                        <div class="space-y-1">
                                            <x-input-label value="Extension (JR., SR)" class="text-[9px] font-black uppercase text-gray-400 tracking-tighter" />
                                            <x-text-input class="block w-full text-sm font-bold border-gray-100 bg-indigo-50/30" name="personal[name_extension]" :value="old('personal.name_extension', $employee->pdsPersonal?->name_extension)" placeholder="e.g. JR." />
                                        </div>
                                    </div>

                                    <!-- 3. DOB & 4. POB -->
                                    <div class="contents">
                                        <div class="space-y-1">
                                            <x-input-label value="Date of Birth" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <x-text-input type="date" class="block w-full text-sm font-bold border-gray-100" name="personal[date_of_birth]" :value="old('personal.date_of_birth', $employee->pdsPersonal?->date_of_birth)" />
                                        </div>
                                        <div class="space-y-1">
                                            <x-input-label value="Place of Birth" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <x-text-input class="block w-full text-sm font-bold border-gray-100" name="personal[place_of_birth]" :value="old('personal.place_of_birth', $employee->pdsPersonal?->place_of_birth)" />
                                        </div>
                                    </div>

                                    <!-- 5. Sex & 6. Civil Status -->
                                    <div class="contents">
                                        <div class="space-y-1">
                                            <x-input-label value="Sex" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <select name="personal[sex]" class="w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold">
                                                <option value="">Select</option>
                                                <option value="Male" {{ old('personal.sex', $employee->pdsPersonal?->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('personal.sex', $employee->pdsPersonal?->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <x-input-label value="Civil Status" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            @php
                                                $currCivilStatus = strtolower(old('personal.civil_status', $employee->pdsPersonal?->civil_status ?? ''));
                                            @endphp
                                            <select name="personal[civil_status]" class="w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold">
                                                <option value="">Select</option>
                                                <option value="single" {{ $currCivilStatus == 'single' ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ $currCivilStatus == 'married' ? 'selected' : '' }}>Married</option>
                                                <option value="widow" {{ in_array($currCivilStatus, ['widow', 'widowed']) ? 'selected' : '' }}>Widowed</option>
                                                <option value="separated" {{ $currCivilStatus == 'separated' ? 'selected' : '' }}>Separated</option>
                                                <option value="other" {{ $currCivilStatus == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 7. Height, 8. Weight, 9. Blood -->
                                    <div class="contents">
                                        <div class="space-y-1">
                                            <x-input-label value="Height (m)" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <x-text-input class="block w-full text-sm font-bold border-gray-100" name="personal[height_m]" :value="old('personal.height_m', $employee->pdsPersonal?->height_m)" />
                                        </div>
                                        <div class="space-y-1">
                                            <x-input-label value="Weight (kg)" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <x-text-input class="block w-full text-sm font-bold border-gray-100" name="personal[weight_kg]" :value="old('personal.weight_kg', $employee->pdsPersonal?->weight_kg)" />
                                        </div>
                                        <div class="space-y-1">
                                            <x-input-label value="Blood Type" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <x-text-input class="block w-full text-sm font-bold border-gray-100" name="personal[blood_type]" :value="old('personal.blood_type', $employee->pdsPersonal?->blood_type)" />
                                        </div>
                                    </div>

                                    <!-- IDs 10-15 -->
                                    <div class="col-span-full border-t border-slate-100 pt-4">
                                        <div class="pds-field-table pds-field-table-4">
                                        <div class="space-y-1"><x-input-label value="UMID ID NO." class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[umid_no]" :value="old('personal.umid_no', $employee->pdsPersonal?->umid_no)" /></div>
                                        <div class="space-y-1"><x-input-label value="PAG-IBIG ID NO." class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[pagibig_id_no]" :value="old('personal.pagibig_id_no', $employee->pdsPersonal?->pagibig_id_no)" /></div>
                                        <div class="space-y-1"><x-input-label value="PHILHEALTH NO." class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[philhealth_no]" :value="old('personal.philhealth_no', $employee->pdsPersonal?->philhealth_no)" /></div>
                                        <div class="space-y-1"><x-input-label value="SSS NO." class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[sss_no]" :value="old('personal.sss_no', $employee->pdsPersonal?->sss_no)" /></div>
                                        <div class="space-y-1"><x-input-label value="PhilSys No. (PSN)" class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[philsys_no]" :value="old('personal.philsys_no', $employee->pdsPersonal?->philsys_no)" /></div>
                                        <div class="space-y-1"><x-input-label value="TIN NO." class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[tin_no]" :value="old('personal.tin_no', $employee->pdsPersonal?->tin_no)" /></div>
                                            <div class="space-y-1"><x-input-label value="AGENCY EMPLOYEE NO." class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[agency_employee_no]" :value="old('personal.agency_employee_no', $employee->pdsPersonal?->agency_employee_no)" /></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-8">
                                    <!-- 16. Citizenship -->
                                    <div class="space-y-4 rounded-lg border border-slate-200 p-5">
                                        <x-input-label value="Citizenship" class="text-[10px] font-black uppercase text-indigo-700 tracking-wider" />
                                        <div class="flex gap-6 items-center">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="personal[citizenship]" value="Filipino" class="text-indigo-600 focus:ring-indigo-500" {{ (old('personal.citizenship', $employee->pdsPersonal?->citizenship) == 'Filipino' || !old('personal.citizenship', $employee->pdsPersonal?->citizenship)) ? 'checked' : '' }}>
                                                <span class="ml-2 text-xs font-bold text-gray-700">Filipino</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="personal[citizenship]" value="Dual Citizenship" class="text-indigo-600 focus:ring-indigo-500" {{ old('personal.citizenship', $employee->pdsPersonal?->citizenship) == 'Dual Citizenship' ? 'checked' : '' }}>
                                                <span class="ml-2 text-xs font-bold text-gray-700">Dual Citizenship</span>
                                            </label>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <select name="personal[citizenship_type]" class="w-full border-gray-100 rounded-xl text-[10px] font-bold">
                                                <option value="">By Birth / Naturalization</option>
                                                <option value="By Birth" {{ old('personal.citizenship_type', $employee->pdsPersonal?->citizenship_type) == 'By Birth' ? 'selected' : '' }}>By Birth</option>
                                                <option value="By Naturalization" {{ old('personal.citizenship_type', $employee->pdsPersonal?->citizenship_type) == 'By Naturalization' ? 'selected' : '' }}>By Naturalization</option>
                                            </select>
                                            <x-text-input placeholder="Country" class="w-full text-[10px] font-bold border-gray-100" name="personal[citizenship_country]" :value="old('personal.citizenship_country', $employee->pdsPersonal?->citizenship_country)" />
                                        </div>
                                    </div>

                                    <!-- 17. Residential Address -->
                                    <div class="rounded-lg border border-slate-200 p-5">
                                        <x-input-label value="Residential Address" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                        <div class="pds-field-table pds-field-table-4 mt-5">
                                            <x-text-input placeholder="House/Block/Lot No." class="text-xs font-bold" name="personal[res_house_no]" :value="old('personal.res_house_no', $employee->pdsPersonal?->res_house_no)" />
                                            <x-text-input placeholder="Street" class="text-xs font-bold" name="personal[res_street]" :value="old('personal.res_street', $employee->pdsPersonal?->res_street)" />
                                            <x-text-input placeholder="Subdivision/Village" class="text-xs font-bold" name="personal[res_subdivision]" :value="old('personal.res_subdivision', $employee->pdsPersonal?->res_subdivision)" />
                                            <x-text-input placeholder="Barangay" class="text-xs font-bold" name="personal[res_barangay]" :value="old('personal.res_barangay', $employee->pdsPersonal?->res_barangay)" />
                                            <x-text-input placeholder="City/Municipality" class="text-xs font-bold" name="personal[res_city]" :value="old('personal.res_city', $employee->pdsPersonal?->res_city)" />
                                            <x-text-input placeholder="Province" class="text-xs font-bold" name="personal[res_province]" :value="old('personal.res_province', $employee->pdsPersonal?->res_province)" />
                                            <x-text-input placeholder="Zip Code" class="text-xs font-bold" name="personal[res_zip_code]" :value="old('personal.res_zip_code', $employee->pdsPersonal?->res_zip_code)" />
                                        </div>
                                    </div>

                                    <!-- 18. Permanent Address -->
                                    <div class="rounded-lg border border-slate-200 p-5">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <x-input-label value="Permanent Address" class="text-[10px] font-black uppercase text-gray-500 tracking-wider" />
                                            <label class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                <input type="checkbox" id="sameAsResidentialAddress" class="rounded border-slate-300 text-indigo-700 focus:ring-indigo-500">
                                                Same as residential address
                                            </label>
                                        </div>
                                        <div class="pds-field-table pds-field-table-4 mt-5">
                                            <x-text-input placeholder="House/Block/Lot No." class="text-xs font-bold" name="personal[perm_house_no]" :value="old('personal.perm_house_no', $employee->pdsPersonal?->perm_house_no)" />
                                            <x-text-input placeholder="Street" class="text-xs font-bold" name="personal[perm_street]" :value="old('personal.perm_street', $employee->pdsPersonal?->perm_street)" />
                                            <x-text-input placeholder="Subdivision/Village" class="text-xs font-bold" name="personal[perm_subdivision]" :value="old('personal.perm_subdivision', $employee->pdsPersonal?->perm_subdivision)" />
                                            <x-text-input placeholder="Barangay" class="text-xs font-bold" name="personal[perm_barangay]" :value="old('personal.perm_barangay', $employee->pdsPersonal?->perm_barangay)" />
                                            <x-text-input placeholder="City/Municipality" class="text-xs font-bold" name="personal[perm_city]" :value="old('personal.perm_city', $employee->pdsPersonal?->perm_city)" />
                                            <x-text-input placeholder="Province" class="text-xs font-bold" name="personal[perm_province]" :value="old('personal.perm_province', $employee->pdsPersonal?->perm_province)" />
                                            <x-text-input placeholder="Zip Code" class="text-xs font-bold" name="personal[perm_zip_code]" :value="old('personal.perm_zip_code', $employee->pdsPersonal?->perm_zip_code)" />
                                        </div>
                                    </div>

                                    <!-- 19-21 Contact -->
                                    <div class="pds-field-table pds-field-table-3 border-t border-slate-100 pt-4">
                                        <div class="space-y-1"><x-input-label value="Telephone No." class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[telephone_no]" :value="old('personal.telephone_no', $employee->pdsPersonal?->telephone_no)" /></div>
                                        <div class="space-y-1"><x-input-label value="Mobile No." class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[mobile_no]" :value="old('personal.mobile_no', $employee->pdsPersonal?->mobile_no)" /></div>
                                        <div class="space-y-1"><x-input-label value="E-mail Address" class="text-[9px] font-black text-gray-400" /><x-text-input class="w-full text-xs font-bold border-gray-100" name="personal[email_address]" :value="old('personal.email_address', $employee->pdsPersonal?->email_address)" /></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section II: Family Background -->
                    <div x-show="tab === 'family'" x-cloak class="space-y-10 animate-fade-in">
                        <div>
                            <div class="pds-section-heading flex justify-between items-center border border-black bg-gray-200 px-3 py-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Family Background</h3>
                                </div>
                                @php $familyReview = $employee->pdsSectionReviews->where('section_name', 'Family Background')->first(); @endphp
                                @if($familyReview && $familyReview->remarks)
                                    <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-1 rounded font-bold border border-yellow-200">Has HR Remarks</span>
                                @endif
                            </div>
                            @if($familyReview && $familyReview->remarks)
                                <div class="mb-6 bg-yellow-50/50 border border-yellow-200 p-4 rounded-xl flex gap-3 items-start shadow-sm">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <div>
                                        <span class="block text-[10px] font-black text-yellow-800 uppercase tracking-widest mb-1">HR Remarks ({{ ucfirst($familyReview->status) }})</span>
                                        <p class="text-sm font-medium text-yellow-900 leading-relaxed">{{ $familyReview->remarks }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-1"><x-input-label value="Spouse Surname" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" /><x-text-input class="block w-full text-sm font-bold border-gray-100" name="family[spouse_surname]" :value="$employee->pdsFamily?->spouse_surname" /></div>
                                <div class="space-y-1"><x-input-label value="Spouse First Name" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" /><x-text-input class="block w-full text-sm font-bold border-gray-100" name="family[spouse_firstname]" :value="$employee->pdsFamily?->spouse_firstname" /></div>
                                <div class="space-y-1"><x-input-label value="Occupation" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" /><x-text-input class="block w-full text-sm font-bold border-gray-100" name="family[spouse_occupation]" :value="$employee->pdsFamily?->spouse_occupation" /></div>
                            </div>

                            <!-- Dynamic Children Rows -->
                            <div class="mt-8 bg-gray-50/50 p-6 rounded-2xl border-2 border-gray-100/50">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-black text-gray-700 uppercase text-[10px] tracking-widest">Children</h4>
                                    <button type="button" @click="children.push({fullname: '', date_of_birth: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-white border border-indigo-200 text-indigo-700 px-3 py-1.5 rounded-lg hover:bg-indigo-50 transition">+ Add Child</button>
                                </div>
                                <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="text-left text-[9px] font-black uppercase text-gray-400 bg-gray-50">
                                                <th class="px-4 py-2">Full Name</th>
                                                <th class="px-4 py-2">Date of Birth</th>
                                                <th class="px-4 py-2 w-10"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            <template x-for="(child, index) in children" :key="index">
                                                <tr>
                                                    <td class="px-2 py-1"><x-text-input placeholder="Juan Dela Cruz" class="w-full text-xs font-bold border-transparent focus:border-indigo-100 focus:ring-0" x-model="child.fullname" ::name="`children[${index}][fullname]`" /></td>
                                                    <td class="px-2 py-1"><x-text-input type="date" class="w-full text-xs font-bold border-transparent focus:border-indigo-100 focus:ring-0" x-model="child.date_of_birth" ::name="`children[${index}][date_of_birth]`" /></td>
                                                    <td class="px-2 py-1"><button type="button" @click="children.splice(index, 1)" class="text-red-400 hover:text-red-600 font-bold transition">&times;</button></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section III: Education -->
                    <div x-show="tab === 'education'" x-cloak class="space-y-12 animate-fade-in">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-indigo-700 pb-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Educational Background</h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php $educationReview = $employee->pdsSectionReviews->where('section_name', 'Educational Background')->first(); @endphp
                                    @if($educationReview && $educationReview->remarks)
                                        <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-1 rounded font-bold border border-yellow-200">Has HR Remarks</span>
                                    @endif
                                    <button type="button" @click="education.push({level: '', school_name: '', course: '', period_from: '', period_to: '', highest_level: '', year_graduated: '', honors: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Row</button>
                                </div>
                            </div>
                            @if($educationReview && $educationReview->remarks)
                                <div class="mb-6 bg-yellow-50/50 border border-yellow-200 p-4 rounded-xl flex gap-3 items-start shadow-sm">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <div>
                                        <span class="block text-[10px] font-black text-yellow-800 uppercase tracking-widest mb-1">HR Remarks ({{ ucfirst($educationReview->status) }})</span>
                                        <p class="text-sm font-medium text-yellow-900 leading-relaxed">{{ $educationReview->remarks }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="bg-white border-2 border-gray-50 rounded-2xl overflow-x-auto shadow-sm">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-left text-[9px] font-black uppercase text-gray-400 bg-gray-50">
                                            <th class="px-2 py-3 border-r border-gray-50">Level</th>
                                            <th class="px-2 py-3 border-r border-gray-50">School Name</th>
                                            <th class="px-2 py-3 border-r border-gray-50">Course</th>
                                            <th class="px-2 py-3 border-r border-gray-50">From</th>
                                            <th class="px-2 py-3 border-r border-gray-50">To</th>
                                            <th class="px-2 py-3 border-r border-gray-50">Year</th>
                                            <th class="px-2 py-3 border-r border-gray-50">Honors</th>
                                            <th class="px-2 py-3 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 font-bold text-gray-800">
                                        <template x-for="(edu, index) in education" :key="index">
                                            <tr>
                                                <td class="p-0.5"><x-text-input class="w-full text-[10px] font-bold border-transparent focus:border-indigo-100 bg-transparent px-2" x-model="edu.level" ::name="`education[${index}][level]`" /></td>
                                                <td class="p-0.5"><x-text-input class="w-full text-[10px] font-bold border-transparent focus:border-indigo-100 bg-transparent px-2" x-model="edu.school_name" ::name="`education[${index}][school_name]`" /></td>
                                                <td class="p-0.5"><x-text-input class="w-full text-[10px] font-bold border-transparent focus:border-indigo-100 bg-transparent px-2" x-model="edu.course" ::name="`education[${index}][course]`" /></td>
                                                <td class="p-0.5"><x-text-input class="w-full text-[10px] font-bold border-transparent focus:border-indigo-100 bg-transparent px-2" x-model="edu.period_from" ::name="`education[${index}][period_from]`" /></td>
                                                <td class="p-0.5"><x-text-input class="w-full text-[10px] font-bold border-transparent focus:border-indigo-100 bg-transparent px-2" x-model="edu.period_to" ::name="`education[${index}][period_to]`" /></td>
                                                <td class="p-0.5"><x-text-input class="w-full text-[10px] font-bold border-transparent focus:border-indigo-100 bg-transparent px-2" x-model="edu.year_graduated" ::name="`education[${index}][year_graduated]`" /></td>
                                                <td class="p-0.5"><x-text-input class="w-full text-[10px] font-bold border-transparent focus:border-indigo-100 bg-transparent px-2" x-model="edu.honors" ::name="`education[${index}][honors]`" /></td>
                                                <td class="p-0.5 text-center"><button type="button" @click="education.splice(index, 1)" class="text-red-400 hover:text-red-600">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section IV: Civil Service Eligibility -->
                    <div x-show="tab === 'eligibility'" x-cloak class="space-y-12 animate-fade-in">
                        <div>
                            <div class="flex justify-between items-center border-b-2 border-indigo-700 pb-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Civil Service Eligibility</h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php $eligibilityReview = $employee->pdsSectionReviews->where('section_name', 'Civil Service Eligibility')->first(); @endphp
                                    @if($eligibilityReview && $eligibilityReview->remarks)
                                        <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-1 rounded font-bold border border-yellow-200">Has HR Remarks</span>
                                    @endif
                                    <button type="button" @click="eligibility.push({title: '', rating: '', date_of_exam: '', place_of_exam: '', license_number: '', license_validity: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Eligibility</button>
                                </div>
                            </div>
                            @if($eligibilityReview && $eligibilityReview->remarks)
                                <div class="mb-6 bg-yellow-50/50 border border-yellow-200 p-4 rounded-xl flex gap-3 items-start shadow-sm">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <div>
                                        <span class="block text-[10px] font-black text-yellow-800 uppercase tracking-widest mb-1">HR Remarks ({{ ucfirst($eligibilityReview->status) }})</span>
                                        <p class="text-sm font-medium text-yellow-900 leading-relaxed">{{ $eligibilityReview->remarks }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="bg-white border-2 border-gray-50 rounded-2xl overflow-hidden shadow-sm">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-left text-[9px] font-black uppercase text-gray-400 bg-gray-50">
                                            <th class="px-4 py-3">Eligibility Title</th>
                                            <th class="px-4 py-3">Rating</th>
                                            <th class="px-4 py-3">Date</th>
                                            <th class="px-4 py-3 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 font-bold">
                                        <template x-for="(eli, index) in eligibility" :key="index">
                                            <tr>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="eli.title" ::name="`eligibility[${index}][title]`" /></td>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="eli.rating" ::name="`eligibility[${index}][rating]`" /></td>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="eli.date_of_exam" ::name="`eligibility[${index}][date_of_exam]`" /></td>
                                                <td class="p-1 text-center"><button type="button" @click="eligibility.splice(index, 1)" class="text-red-400 hover:text-red-600">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section V: Work Experience -->
                    <div x-show="tab === 'work'" x-cloak class="space-y-12 animate-fade-in">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-indigo-700 pb-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Work Experience</h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php $workReview = $employee->pdsSectionReviews->where('section_name', 'Work Experience')->first(); @endphp
                                    @if($workReview && $workReview->remarks)
                                        <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-1 rounded font-bold border border-yellow-200">Has HR Remarks</span>
                                    @endif
                                    <button type="button" @click="work.push({date_from: '', date_to: '', position_title: '', company: '', monthly_salary: '', salary_grade: '', appointment_status: '', is_gov_service: 0})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Work</button>
                                </div>
                            </div>
                            @if($workReview && $workReview->remarks)
                                <div class="mb-6 bg-yellow-50/50 border border-yellow-200 p-4 rounded-xl flex gap-3 items-start shadow-sm">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <div>
                                        <span class="block text-[10px] font-black text-yellow-800 uppercase tracking-widest mb-1">HR Remarks ({{ ucfirst($workReview->status) }})</span>
                                        <p class="text-sm font-medium text-yellow-900 leading-relaxed">{{ $workReview->remarks }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="bg-white border-2 border-gray-50 rounded-2xl overflow-hidden shadow-sm">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-left text-[9px] font-black uppercase text-gray-400 bg-gray-50">
                                            <th class="px-4 py-3">From</th>
                                            <th class="px-4 py-3">To</th>
                                            <th class="px-4 py-3">Position</th>
                                            <th class="px-4 py-3">Company</th>
                                            <th class="px-4 py-3">Salary</th>
                                            <th class="px-4 py-3 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 font-bold">
                                        <template x-for="(w, index) in work" :key="index">
                                            <tr>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="w.date_from" ::name="`work_experience[${index}][date_from]`" /></td>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="w.date_to" ::name="`work_experience[${index}][date_to]`" /></td>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="w.position_title" ::name="`work_experience[${index}][position_title]`" /></td>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="w.company" ::name="`work_experience[${index}][company]`" /></td>
                                                <td class="p-1"><x-text-input type="number" step="0.01" class="w-full text-[10px] font-bold border-transparent px-3" x-model="w.monthly_salary" ::name="`work_experience[${index}][monthly_salary]`" /></td>
                                                <td class="p-1 text-center"><button type="button" @click="work.splice(index, 1)" class="text-red-400 hover:text-red-600">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section VI: Voluntary Work -->
                    <div x-show="tab === 'voluntary'" x-cloak class="space-y-12 animate-fade-in">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-indigo-700 pb-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Voluntary Work or Involvement</h3>
                                </div>
                                <button type="button" @click="voluntary.push({organization_name: '', date_from: '', date_to: '', number_of_hours: '', position: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Voluntary Work</button>
                            </div>
                            <div class="bg-white border-2 border-gray-50 rounded-2xl overflow-hidden shadow-sm">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-left text-[9px] font-black uppercase text-gray-400 bg-gray-50">
                                            <th class="px-4 py-3">Organization</th>
                                            <th class="px-4 py-3">From</th>
                                            <th class="px-4 py-3">To</th>
                                            <th class="px-4 py-3">Hours</th>
                                            <th class="px-4 py-3">Position / Nature of Work</th>
                                            <th class="px-4 py-3 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 font-bold">
                                        <template x-for="(vol, index) in voluntary" :key="index">
                                            <tr>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="vol.organization_name" ::name="`voluntary[${index}][organization_name]`" /></td>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="vol.date_from" ::name="`voluntary[${index}][date_from]`" /></td>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="vol.date_to" ::name="`voluntary[${index}][date_to]`" /></td>
                                                <td class="p-1"><x-text-input type="number" class="w-full text-[10px] font-bold border-transparent px-3" x-model="vol.number_of_hours" ::name="`voluntary[${index}][number_of_hours]`" /></td>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="vol.position" ::name="`voluntary[${index}][position]`" /></td>
                                                <td class="p-1 text-center"><button type="button" @click="voluntary.splice(index, 1)" class="text-red-400 hover:text-red-600">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section VII: Learning and Development -->
                    <div x-show="tab === 'training'" x-cloak class="space-y-12 animate-fade-in">
                         <div>
                            <div class="pds-section-heading flex justify-between items-center border border-black bg-gray-200 px-3 py-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">L&D / Training Interventions</h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php $trainingReview = $employee->pdsSectionReviews->where('section_name', 'Training')->first(); @endphp
                                    @if($trainingReview && $trainingReview->remarks)
                                        <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-1 rounded font-bold border border-yellow-200">Has HR Remarks</span>
                                    @endif
                                    <button type="button" @click="training.push({title: '', date_from: '', date_to: '', number_of_hours: '', attachment_path: '', type: '', conducted_by: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Training</button>
                                </div>
                            </div>
                            @if($trainingReview && $trainingReview->remarks)
                                <div class="mb-6 bg-yellow-50/50 border border-yellow-200 p-4 rounded-xl flex gap-3 items-start shadow-sm">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <div>
                                        <span class="block text-[10px] font-black text-yellow-800 uppercase tracking-widest mb-1">HR Remarks ({{ ucfirst($trainingReview->status) }})</span>
                                        <p class="text-sm font-medium text-yellow-900 leading-relaxed">{{ $trainingReview->remarks }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="bg-white border-2 border-gray-50 rounded-2xl overflow-hidden shadow-sm">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-left text-[9px] font-black uppercase text-gray-400 bg-gray-50">
                                            <th class="px-4 py-3">Title of Program</th>
                                            <th class="px-4 py-3">From</th>
                                            <th class="px-4 py-3">To</th>
                                            {{-- Change: replaced Hours with Attachment for PDS training records. --}}
                                            <th class="px-4 py-3">Attachment</th>
                                            <th class="px-4 py-3 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 font-bold">
                                        <template x-for="(t, index) in training" :key="index">
                                            <tr>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="t.title" ::name="`training[${index}][title]`" /></td>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="t.date_from" ::name="`training[${index}][date_from]`" /></td>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="t.date_to" ::name="`training[${index}][date_to]`" /></td>
                                                <td class="p-1">
                                                    <input type="hidden" :name="`training[${index}][existing_attachment_path]`" :value="t.attachment_path || ''">
                                                    <input type="file" :name="`training[${index}][attachment]`" accept=".pdf,application/pdf" class="block w-full text-[10px] font-bold text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:text-indigo-700 hover:file:bg-indigo-100">
                                                    <template x-if="t.attachment_path">
                                                        <a :href="`/storage/${t.attachment_path}`" target="_blank" class="mt-1 inline-flex text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800">
                                                            View current file
                                                        </a>
                                                    </template>
                                                </td>
                                                <td class="p-1 text-center"><button type="button" @click="training.splice(index, 1)" class="text-red-400 hover:text-red-600">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section VIII: Other Information -->
                    <div x-show="tab === 'otherinfo'" x-cloak class="space-y-12 animate-fade-in">
                        <div>
                            <div class="pds-section-heading flex justify-between items-center border border-black bg-gray-200 px-3 py-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Other Information</h3>
                                </div>
                                <button type="button" @click="others.push({type: 'Skill', description: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Info</button>
                            </div>
                            <div class="bg-white border-2 border-gray-50 rounded-2xl overflow-hidden shadow-sm">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-left text-[9px] font-black uppercase text-gray-400 bg-gray-50">
                                            <th class="px-4 py-3">Type</th>
                                            <th class="px-4 py-3">Details</th>
                                            <th class="px-4 py-3 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 font-bold">
                                        <template x-for="(other, index) in others" :key="index">
                                            <tr>
                                                <td class="p-1">
                                                    <select class="w-full text-[10px] font-bold border-transparent px-3" x-model="other.type" :name="`others[${index}][type]`">
                                                        <option value="Skill">Special Skill / Hobby</option>
                                                        <option value="Distinction">Non-Academic Distinction</option>
                                                        <option value="Membership">Membership in Association</option>
                                                    </select>
                                                </td>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="other.description" ::name="`others[${index}][description]`" /></td>
                                                <td class="p-1 text-center"><button type="button" @click="others.splice(index, 1)" class="text-red-400 hover:text-red-600">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <div class="pds-section-heading flex justify-between items-center border border-black bg-gray-200 px-3 py-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">References</h3>
                                </div>
                                @php $referenceReview = $employee->pdsSectionReviews->where('section_name', 'References')->first(); @endphp
                                @if($referenceReview && $referenceReview->remarks)
                                    <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-1 rounded font-bold border border-yellow-200">Has HR Remarks</span>
                                @endif
                            </div>
                            @if($referenceReview && $referenceReview->remarks)
                                <div class="mb-6 bg-yellow-50/50 border border-yellow-200 p-4 rounded-xl flex gap-3 items-start shadow-sm">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <div>
                                        <span class="block text-[10px] font-black text-yellow-800 uppercase tracking-widest mb-1">HR Remarks ({{ ucfirst($referenceReview->status) }})</span>
                                        <p class="text-sm font-medium text-yellow-900 leading-relaxed">{{ $referenceReview->remarks }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="bg-gray-50/50 p-6 rounded-2xl border-2 border-gray-100/50 grid grid-cols-1 md:grid-cols-3 gap-6 shadow-inner">
                                <template x-for="(ref, index) in references" :key="index">
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-3">
                                        <div class="space-y-1">
                                            <x-input-label value="Name" class="text-[9px] font-black uppercase text-gray-400 tracking-widest" />
                                            <x-text-input class="w-full text-[10px] font-bold border-gray-50 focus:border-indigo-100" x-model="ref.name" ::name="`references[${index}][name]`" />
                                        </div>
                                        <div class="space-y-1">
                                            <x-input-label value="Address" class="text-[9px] font-black uppercase text-gray-400 tracking-widest" />
                                            <x-text-input class="w-full text-[10px] font-bold border-gray-50 focus:border-indigo-100" x-model="ref.address" ::name="`references[${index}][address]`" />
                                        </div>
                                        <div class="space-y-1">
                                            <x-input-label value="Telephone" class="text-[9px] font-black uppercase text-gray-400 tracking-widest" />
                                            <x-text-input class="w-full text-[10px] font-bold border-gray-50 focus:border-indigo-100" x-model="ref.telephone_no" ::name="`references[${index}][telephone_no]`" />
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>



                    <!-- Section IX: Questions, Declaration, ID, Photo, and Signature -->
                    <div x-show="tab === 'declaration'" x-cloak class="space-y-10 animate-fade-in">
                        <div>
                            <div class="pds-section-heading flex justify-between items-center border border-black bg-gray-200 px-3 py-2 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Questions 34-42, Declaration, ID, Photo, and Signature</h3>
                                </div>
                            </div>

                            @php
                                $questionRows = [
                                    ['key' => 'q34_a', 'label' => '34a. Related by consanguinity or affinity within the third degree?', 'details' => 'q34_details'],
                                    ['key' => 'q34_b', 'label' => '34b. Related by consanguinity or affinity within the fourth degree?', 'details' => 'q34_details'],
                                    ['key' => 'q35_a', 'label' => '35a. Found guilty of any administrative offense?', 'details' => 'q35_details'],
                                    ['key' => 'q35_b', 'label' => '35b. Criminally charged before any court?', 'details' => null],
                                    ['key' => 'q36', 'label' => '36. Convicted of any crime or violation of any law?', 'details' => 'q36_details'],
                                    ['key' => 'q37', 'label' => '37. Separated from service in any mode?', 'details' => 'q37_details'],
                                    ['key' => 'q38_a', 'label' => '38a. Candidate in a national or local election held within the last year?', 'details' => 'q38_a_details'],
                                    ['key' => 'q38_b', 'label' => '38b. Resigned from government service before the last election?', 'details' => 'q38_b_details'],
                                    ['key' => 'q39', 'label' => '39. Acquired immigrant or permanent resident status in another country?', 'details' => 'q39_details'],
                                    ['key' => 'q40_a', 'label' => '40a. Member of an indigenous group?', 'details' => 'q40_a_details'],
                                    ['key' => 'q40_b', 'label' => '40b. Person with disability?', 'details' => 'q40_b_details'],
                                    ['key' => 'q40_c', 'label' => '40c. Solo parent?', 'details' => 'q40_c_details'],
                                ];
                            @endphp

                            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 text-[9px] font-black uppercase tracking-widest text-gray-400">
                                            <th class="px-4 py-3 text-left">Question</th>
                                            <th class="w-24 px-4 py-3 text-center">Yes</th>
                                            <th class="w-24 px-4 py-3 text-center">No</th>
                                            <th class="px-4 py-3 text-left">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 font-bold">
                                        @foreach($questionRows as $row)
                                            @php $current = old('questionnaire.'.$row['key'], $employee->pdsQuestionnaire?->{$row['key']}); @endphp
                                            <tr>
                                                <td class="px-4 py-3 text-gray-700">{{ $row['label'] }}</td>
                                                <td class="px-4 py-3 text-center"><input type="radio" name="questionnaire[{{ $row['key'] }}]" value="1" {{ (string) $current === '1' ? 'checked' : '' }}></td>
                                                <td class="px-4 py-3 text-center"><input type="radio" name="questionnaire[{{ $row['key'] }}]" value="0" {{ (string) $current === '0' || $current === 0 || $current === false ? 'checked' : '' }}></td>
                                                <td class="px-4 py-3">
                                                    @if($row['key'] === 'q35_b')
                                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                            <x-text-input type="date" class="w-full text-xs font-bold border-gray-100" name="questionnaire[q35_date_filed]" :value="old('questionnaire.q35_date_filed', $employee->pdsQuestionnaire?->q35_date_filed)" />
                                                            <x-text-input class="w-full text-xs font-bold border-gray-100" name="questionnaire[q35_status]" :value="old('questionnaire.q35_status', $employee->pdsQuestionnaire?->q35_status)" placeholder="Status of case" />
                                                        </div>
                                                    @elseif($row['details'])
                                                        <x-text-input class="w-full text-xs font-bold border-gray-100" name="questionnaire[{{ $row['details'] }}]" :value="old('questionnaire.'.$row['details'], $employee->pdsQuestionnaire?->{$row['details']})" placeholder="N/A if not applicable" />
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
                                <div class="space-y-4 rounded-xl border border-gray-100 bg-gray-50/50 p-5">
                                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-500">Government Issued ID</h4>
                                    <x-text-input class="w-full text-xs font-bold border-gray-100" name="gov_id[id_type]" :value="old('gov_id.id_type', $employee->pdsGovId?->id_type)" placeholder="ID Type" />
                                    <x-text-input class="w-full text-xs font-bold border-gray-100" name="gov_id[id_no]" :value="old('gov_id.id_no', $employee->pdsGovId?->id_no)" placeholder="ID / License / Passport No." />
                                    <x-text-input class="w-full text-xs font-bold border-gray-100" name="gov_id[date_place_issuance]" :value="old('gov_id.date_place_issuance', $employee->pdsGovId?->date_place_issuance)" placeholder="Date / Place of Issuance" />
                                </div>
                                <div class="space-y-4 rounded-xl border border-gray-100 bg-gray-50/50 p-5">
                                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-500">Official Photo</h4>
                                    <div class="mx-auto flex h-36 w-28 items-center justify-center overflow-hidden border border-black bg-white text-[10px] font-black uppercase text-gray-400">
                                        <template x-if="photoPreview"><img :src="photoPreview" class="h-full w-full object-cover" alt="PDS photo preview"></template>
                                        <span x-show="!photoPreview">Photo</span>
                                    </div>
                                    <input type="file" name="pds_photo" accept="image/jpeg,image/png,image/jpg" @change="photoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : photoPreview" class="block w-full text-[10px] font-bold text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:text-indigo-700">
                                </div>
                                <div class="space-y-4 rounded-xl border border-gray-100 bg-gray-50/50 p-5">
                                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-500">Signature</h4>
                                    <div class="flex h-20 items-center justify-center overflow-hidden border border-black bg-white text-[10px] font-black uppercase text-gray-400">
                                        <template x-if="signaturePreview"><img :src="signaturePreview" class="h-full w-full object-contain" alt="PDS signature preview"></template>
                                        <span x-show="!signaturePreview">Signature</span>
                                    </div>
                                    <input type="file" name="pds_signature" accept="image/png,.png" @change="signaturePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : signaturePreview" class="block w-full text-[10px] font-bold text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:text-indigo-700">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex justify-end gap-3 border-t-2 border-gray-50 pt-8 print:hidden">
                        <a href="{{ route('pds.index') }}" class="inline-flex items-center px-6 py-2 bg-white border-2 border-gray-200 rounded-xl font-black text-[10px] uppercase tracking-widest text-gray-500 hover:bg-gray-50 transition duration-150">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-8 py-2.5 bg-indigo-700 border-2 border-indigo-800 rounded-xl font-black text-[10px] uppercase tracking-widest text-white hover:bg-indigo-800 shadow-lg shadow-indigo-100 transition duration-150">
                            Save PDS Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sameAddress = document.getElementById('sameAsResidentialAddress');
            if (!sameAddress) return;

            const pairs = [
                ['res_house_no', 'perm_house_no'],
                ['res_street', 'perm_street'],
                ['res_subdivision', 'perm_subdivision'],
                ['res_barangay', 'perm_barangay'],
                ['res_city', 'perm_city'],
                ['res_province', 'perm_province'],
                ['res_zip_code', 'perm_zip_code'],
            ];

            const field = (key) => document.querySelector(`[name="personal[${key}]"]`);
            const copyResidentialAddress = () => {
                pairs.forEach(([resKey, permKey]) => {
                    const source = field(resKey);
                    const target = field(permKey);
                    if (source && target) target.value = source.value;
                });
            };

            sameAddress.addEventListener('change', () => {
                if (sameAddress.checked) copyResidentialAddress();
            });

            pairs.forEach(([resKey]) => {
                const source = field(resKey);
                if (!source) return;
                source.addEventListener('input', () => {
                    if (sameAddress.checked) copyResidentialAddress();
                });
            });
        });
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .pds-line-page .pds-form-body {
            color: #334155;
            font-family: Arial, Helvetica, sans-serif;
        }
        .pds-field-table {
            display: grid;
            column-gap: 1.5rem;
            row-gap: 1.75rem;
            align-items: end;
        }
        .pds-field-table-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .pds-field-table-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        @media (max-width: 1023px) {
            .pds-field-table-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 767px) {
            .pds-field-table-3,
            .pds-field-table-4 {
                grid-template-columns: 1fr;
            }
        }
        .pds-line-page .pds-form-body label span,
        .pds-line-page .pds-form-body .text-gray-500,
        .pds-line-page .pds-form-body .text-gray-400 {
            color: #94a3b8 !important;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0;
        }
        .pds-line-page .pds-form-body input:not([type="radio"]):not([type="checkbox"]):not([type="file"]),
        .pds-line-page .pds-form-body select,
        .pds-line-page .pds-form-body textarea {
    border: 0 !important;
    border-bottom: 1px dashed #94a3b8 !important;
    border-radius: 6px !important;
    background: #eef2ff !important;
    box-shadow: none !important;
    padding: 0.45rem 0.65rem !important;
    font-size: 1rem !important;
    font-weight: 500 !important;
    color: #475569 !important;
        }
        .pds-line-page .pds-form-body select {
            appearance: auto !important;
            -webkit-appearance: menulist !important;
            padding-right: 2rem !important;
            cursor: pointer;
        }
        .pds-line-page .pds-form-body input:focus,
        .pds-line-page .pds-form-body select:focus,
        .pds-line-page .pds-form-body textarea:focus {
            border-bottom-color: #2b428f !important;
            outline: 0 !important;
            box-shadow: none !important;
            --tw-ring-shadow: 0 0 #0000 !important;
        }
        .pds-line-page .pds-form-body table {
            border-collapse: collapse;
        }
        .pds-line-page .pds-form-body th,
        .pds-line-page .pds-form-body td {
            border-color: #e2e8f0 !important;
        }
        .pds-line-page .pds-section-heading {
            border: 0 !important;
            border-bottom: 2px solid #2b428f !important;
            background: transparent !important;
            padding: 0 0 0.5rem 0 !important;
        }
        .pds-line-page .pds-section-heading h3 {
            color: #0f172a !important;
            letter-spacing: 0.03em;
        }
        @media print {
            body { background: #fff !important; }
            .pds-line-page { padding: 0 !important; background: #fff !important; }
            .pds-paper { box-shadow: none !important; border: 0 !important; }
            .pds-form-body { padding: 0.25in !important; }
        }
    </style>
</x-app-layout>