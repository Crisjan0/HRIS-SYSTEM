<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Update Personal Data Sheet') }}
            </h2>
            <div class="text-sm text-gray-500 italic">CS Form No. 212 Revised 2017</div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        tab: 'personal',
        children: {{ $employee->pdsChildren->count() > 0 ? $employee->pdsChildren->toJson() : '[{fullname: \'\', date_of_birth: \'\'}]' }},
        education: {{ $employee->pdsEducation->count() > 0 ? $employee->pdsEducation->toJson() : '[{level: \'\', school_name: \'\', course: \'\', period_from: \'\', period_to: \'\', highest_level: \'\', year_graduated: \'\', honors: \'\'}]' }},
        eligibility: {{ $employee->pdsEligibilities->count() > 0 ? $employee->pdsEligibilities->toJson() : '[{title: \'\', rating: \'\', date_of_exam: \'\', place_of_exam: \'\', license_number: \'\', license_validity: \'\'}]' }},
        work: {{ $employee->pdsWorkExperiences->count() > 0 ? $employee->pdsWorkExperiences->toJson() : '[{date_from: \'\', date_to: \'\', position_title: \'\', company: \'\', monthly_salary: \'\', salary_grade: \'\', appointment_status: \'\', is_gov_service: 0}]' }},
        training: {{ $employee->pdsTrainings->count() > 0 ? $employee->pdsTrainings->toJson() : '[{title: \'\', date_from: \'\', date_to: \'\', number_of_hours: \'\', type: \'\', conducted_by: \'\'}]' }},
        voluntary: {{ $employee->pdsVoluntaryWorks->count() > 0 ? $employee->pdsVoluntaryWorks->toJson() : '[{organization_name: \'\', date_from: \'\', date_to: \'\', number_of_hours: \'\', position: \'\'}]' }},
        others: {{ $employee->pdsOthers->count() > 0 ? $employee->pdsOthers->toJson() : '[{type: \'Skill\', description: \'\'}]' }},
        references: {{ $employee->pdsReferences->count() > 0 ? $employee->pdsReferences->toJson() : '[{name: \'\', address: \'\', telephone_no: \'\'}, {name: \'\', address: \'\', telephone_no: \'\'}, {name: \'\', address: \'\', telephone_no: \'\'}]' }}
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <nav class="flex -mb-px px-6 overflow-x-auto" aria-label="Tabs">
                        <button @click="tab = 'personal'" :class="tab === 'personal' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                            I. Personal & II. Family
                        </button>
                        <button @click="tab = 'education'" :class="tab === 'education' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                            III. Education & IV. Eligibility
                        </button>
                        <button @click="tab = 'work'" :class="tab === 'work' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                            V. Work & VI. Voluntary
                        </button>
                        <button @click="tab = 'others'" :class="tab === 'others' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                            VII. Training & IX. References
                        </button>
                    </nav>
                </div>

                <form action="{{ route('pds.update') }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <!-- Section I & II: Personal & Family -->
                    <div x-show="tab === 'personal'" class="space-y-12">
                        <div>
                            <h3 class="text-xl font-bold text-blue-700 dark:text-blue-400 border-b-2 border-blue-100 pb-2 mb-6 uppercase tracking-wider italic">I. Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="surname" :value="__('Surname')" />
                                    <x-text-input id="surname" class="block mt-1 w-full" type="text" name="personal[surname]" :value="old('personal.surname', $employee->pdsPersonal?->surname ?? $employee->lastname)" />
                                </div>
                                <div>
                                    <x-input-label for="firstname" :value="__('First Name')" />
                                    <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="personal[firstname]" :value="old('personal.firstname', $employee->pdsPersonal?->firstname ?? $employee->firstname)" />
                                </div>
                                <div>
                                    <x-input-label for="middlename" :value="__('Middle Name')" />
                                    <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="personal[middlename]" :value="old('personal.middlename', $employee->pdsPersonal?->middlename ?? $employee->middlename)" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                                <div>
                                    <x-input-label :value="__('Date of Birth')" />
                                    <x-text-input class="block mt-1 w-full" type="date" name="personal[date_of_birth]" :value="old('personal.date_of_birth', $employee->pdsPersonal?->date_of_birth)" />
                                </div>
                                <div>
                                    <x-input-label :value="__('Place of Birth')" />
                                    <x-text-input class="block mt-1 w-full" type="text" name="personal[place_of_birth]" :value="old('personal.place_of_birth', $employee->pdsPersonal?->place_of_birth)" />
                                </div>
                                <div>
                                    <x-input-label :value="__('Sex')" />
                                    <select name="personal[sex]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select Sex</option>
                                        <option value="Male" {{ old('personal.sex', $employee->pdsPersonal?->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('personal.sex', $employee->pdsPersonal?->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label :value="__('Civil Status')" />
                                    <x-text-input class="block mt-1 w-full" type="text" name="personal[civil_status]" :value="old('personal.civil_status', $employee->pdsPersonal?->civil_status)" />
                                </div>
                            </div>

                            <!-- Additional ID Numbers -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                                <div><x-input-label value="GSIS ID NO" /><x-text-input class="block mt-1 w-full" name="personal[gsis_id_no]" :value="$employee->pdsPersonal?->gsis_id_no" /></div>
                                <div><x-input-label value="PAG-IBIG ID NO" /><x-text-input class="block mt-1 w-full" name="personal[pagibig_id_no]" :value="$employee->pdsPersonal?->pagibig_id_no" /></div>
                                <div><x-input-label value="PHILHEALTH NO" /><x-text-input class="block mt-1 w-full" name="personal[philhealth_no]" :value="$employee->pdsPersonal?->philhealth_no" /></div>
                                <div><x-input-label value="SSS NO" /><x-text-input class="block mt-1 w-full" name="personal[sss_no]" :value="$employee->pdsPersonal?->sss_no" /></div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-blue-700 dark:text-blue-400 border-b-2 border-blue-100 pb-2 mb-6 uppercase tracking-wider italic">II. Family Background</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div><x-input-label value="Spouse Surname" /><x-text-input class="block mt-1 w-full" name="family[spouse_surname]" :value="$employee->pdsFamily?->spouse_surname" /></div>
                                <div><x-input-label value="Spouse First Name" /><x-text-input class="block mt-1 w-full" name="family[spouse_firstname]" :value="$employee->pdsFamily?->spouse_firstname" /></div>
                                <div><x-input-label value="Occupation" /><x-text-input class="block mt-1 w-full" name="family[spouse_occupation]" :value="$employee->pdsFamily?->spouse_occupation" /></div>
                            </div>

                            <!-- Dynamic Children Rows -->
                            <div class="mt-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-bold text-gray-700 dark:text-gray-300">Children</h4>
                                    <button type="button" @click="children.push({fullname: '', date_of_birth: ''})" class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">+ Add Child</button>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="text-left text-xs uppercase text-gray-500">
                                                <th class="pb-2">Full Name</th>
                                                <th class="pb-2">Date of Birth</th>
                                                <th class="pb-2 w-10"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(child, index) in children" :key="index">
                                                <tr>
                                                    <td class="pr-2 pb-2"><x-text-input placeholder="Juan Dela Cruz" class="w-full text-sm" x-model="child.fullname" ::name="`children[${index}][fullname]`" /></td>
                                                    <td class="pr-2 pb-2"><x-text-input type="date" class="w-full text-sm" x-model="child.date_of_birth" ::name="`children[${index}][date_of_birth]`" /></td>
                                                    <td class="pb-2"><button type="button" @click="children.splice(index, 1)" class="text-red-500 hover:text-red-700">&times;</button></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section III & IV: Education & Eligibility -->
                    <div x-show="tab === 'education'" class="space-y-12">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-blue-100 pb-2 mb-6">
                                <h3 class="text-xl font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider italic">III. Educational Background</h3>
                                <button type="button" @click="education.push({level: '', school_name: '', course: '', period_from: '', period_to: '', highest_level: '', year_graduated: '', honors: ''})" class="text-sm bg-blue-500 text-white px-3 py-1 rounded">+ Add Row</button>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg overflow-x-auto shadow-inner">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs uppercase text-gray-500 bg-gray-100 dark:bg-gray-800">
                                            <th class="p-2 border border-gray-200">Level</th>
                                            <th class="p-2 border border-gray-200">School Name</th>
                                            <th class="p-2 border border-gray-200">Course</th>
                                            <th class="p-2 border border-gray-200">From</th>
                                            <th class="p-2 border border-gray-200">To</th>
                                            <th class="p-2 border border-gray-200">Year</th>
                                            <th class="p-2 border border-gray-200">Honors</th>
                                            <th class="p-2 border border-gray-200 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(edu, index) in education" :key="index">
                                            <tr>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="edu.level" ::name="`education[${index}][level]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="edu.school_name" ::name="`education[${index}][school_name]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="edu.course" ::name="`education[${index}][course]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="edu.period_from" ::name="`education[${index}][period_from]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="edu.period_to" ::name="`education[${index}][period_to]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="edu.year_graduated" ::name="`education[${index}][year_graduated]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="edu.honors" ::name="`education[${index}][honors]`" /></td>
                                                <td class="p-1 border border-gray-200 text-center"><button type="button" @click="education.splice(index, 1)" class="text-red-500 font-bold">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center border-b-2 border-blue-100 pb-2 mb-6 mt-12">
                                <h3 class="text-xl font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider italic">IV. Civil Service Eligibility</h3>
                                <button type="button" @click="eligibility.push({title: '', rating: '', date_of_exam: '', place_of_exam: '', license_number: '', license_validity: ''})" class="text-sm bg-blue-500 text-white px-3 py-1 rounded">+ Add Eligibility</button>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs uppercase text-gray-500 bg-gray-100 dark:bg-gray-800">
                                            <th class="p-2 border border-gray-200">Eligibility Title</th>
                                            <th class="p-2 border border-gray-200">Rating</th>
                                            <th class="p-2 border border-gray-200">Date</th>
                                            <th class="p-2 border border-gray-200 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(eli, index) in eligibility" :key="index">
                                            <tr>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="eli.title" ::name="`eligibility[${index}][title]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="eli.rating" ::name="`eligibility[${index}][rating]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input type="date" class="w-full text-xs" x-model="eli.date_of_exam" ::name="`eligibility[${index}][date_of_exam]`" /></td>
                                                <td class="p-1 border border-gray-200 text-center"><button type="button" @click="eligibility.splice(index, 1)" class="text-red-500 font-bold">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section V & VI: Work & Voluntary -->
                    <div x-show="tab === 'work'" class="space-y-12">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-blue-100 pb-2 mb-6">
                                <h3 class="text-xl font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider italic">V. Work Experience</h3>
                                <button type="button" @click="work.push({date_from: '', date_to: '', position_title: '', company: '', monthly_salary: '', salary_grade: '', appointment_status: '', is_gov_service: 0})" class="text-sm bg-blue-500 text-white px-3 py-1 rounded">+ Add Work</button>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs uppercase text-gray-500 bg-gray-100 dark:bg-gray-800">
                                            <th class="p-2 border border-gray-200">From</th>
                                            <th class="p-2 border border-gray-200">To</th>
                                            <th class="p-2 border border-gray-200">Position</th>
                                            <th class="p-2 border border-gray-200">Company</th>
                                            <th class="p-2 border border-gray-200">Salary</th>
                                            <th class="p-2 border border-gray-200 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(w, index) in work" :key="index">
                                            <tr>
                                                <td class="p-1 border border-gray-200"><x-text-input type="date" class="w-full text-xs" x-model="w.date_from" ::name="`work_experience[${index}][date_from]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input type="date" class="w-full text-xs" x-model="w.date_to" ::name="`work_experience[${index}][date_to]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="w.position_title" ::name="`work_experience[${index}][position_title]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="w.company" ::name="`work_experience[${index}][company]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input type="number" step="0.01" class="w-full text-xs" x-model="w.monthly_salary" ::name="`work_experience[${index}][monthly_salary]`" /></td>
                                                <td class="p-1 border border-gray-200 text-center"><button type="button" @click="work.splice(index, 1)" class="text-red-500 font-bold">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section VII, VIII, IX: Training, Others, References -->
                    <div x-show="tab === 'others'" class="space-y-12">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-blue-100 pb-2 mb-6">
                                <h3 class="text-xl font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider italic">VII. L&D / Training Interventions</h3>
                                <button type="button" @click="training.push({title: '', date_from: '', date_to: '', number_of_hours: '', type: '', conducted_by: ''})" class="text-sm bg-blue-500 text-white px-3 py-1 rounded">+ Add Training</button>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs uppercase text-gray-500 bg-gray-100 dark:bg-gray-800">
                                            <th class="p-2 border border-gray-200">Title of Program</th>
                                            <th class="p-2 border border-gray-200">From</th>
                                            <th class="p-2 border border-gray-200">To</th>
                                            <th class="p-2 border border-gray-200">Hours</th>
                                            <th class="p-2 border border-gray-200 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(t, index) in training" :key="index">
                                            <tr>
                                                <td class="p-1 border border-gray-200"><x-text-input class="w-full text-xs" x-model="t.title" ::name="`training[${index}][title]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input type="date" class="w-full text-xs" x-model="t.date_from" ::name="`training[${index}][date_from]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input type="date" class="w-full text-xs" x-model="t.date_to" ::name="`training[${index}][date_to]`" /></td>
                                                <td class="p-1 border border-gray-200"><x-text-input type="number" class="w-full text-xs" x-model="t.number_of_hours" ::name="`training[${index}][number_of_hours]`" /></td>
                                                <td class="p-1 border border-gray-200 text-center"><button type="button" @click="training.splice(index, 1)" class="text-red-500 font-bold">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-blue-700 dark:text-blue-400 border-b-2 border-blue-100 pb-2 mb-6 uppercase tracking-wider italic">IX. References</h3>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg">
                                <template x-for="(ref, index) in references" :key="index">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 border-b border-gray-200 pb-4 last:border-0">
                                        <div><x-input-label value="Name" /><x-text-input class="w-full text-sm" x-model="ref.name" ::name="`references[${index}][name]`" /></div>
                                        <div><x-input-label value="Address" /><x-text-input class="w-full text-sm" x-model="ref.address" ::name="`references[${index}][address]`" /></div>
                                        <div><x-input-label value="Telephone" /><x-text-input class="w-full text-sm" x-model="ref.telephone_no" ::name="`references[${index}][telephone_no]`" /></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex justify-end gap-4 border-t pt-8">
                        <a href="{{ route('pds.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <x-primary-button>
                            {{ __('Save PDS Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
