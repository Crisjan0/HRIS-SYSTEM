<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            {{ __('Update Personal Data Sheet (CS Form No. 212)') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white min-h-screen" x-data="{ 
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
            <div class="bg-white border-2 border-gray-100 rounded-2xl overflow-hidden shadow-lg shadow-gray-200/40">
                
                <!-- Tab Navigation: High Contrast -->
                <div class="border-b-2 border-gray-100 bg-gray-50/50">
                    <nav class="flex -mb-px px-4 overflow-x-auto gap-2 py-2" aria-label="Tabs">
                        <button @click="tab = 'personal'" :class="tab === 'personal' ? 'bg-white border-indigo-700 text-indigo-700 shadow-sm' : 'bg-transparent border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-6 border-b-4 font-black text-xs uppercase tracking-widest rounded-t-xl transition-all duration-200">
                            I. Personal & Family
                        </button>
                        <button @click="tab = 'education'" :class="tab === 'education' ? 'bg-white border-indigo-700 text-indigo-700 shadow-sm' : 'bg-transparent border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-6 border-b-4 font-black text-xs uppercase tracking-widest rounded-t-xl transition-all duration-200">
                            III. Education & Eligibility
                        </button>
                        <button @click="tab = 'work'" :class="tab === 'work' ? 'bg-white border-indigo-700 text-indigo-700 shadow-sm' : 'bg-transparent border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-6 border-b-4 font-black text-xs uppercase tracking-widest rounded-t-xl transition-all duration-200">
                            V. Work & Voluntary
                        </button>
                        <button @click="tab = 'others'" :class="tab === 'others' ? 'bg-white border-indigo-700 text-indigo-700 shadow-sm' : 'bg-transparent border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-6 border-b-4 font-black text-xs uppercase tracking-widest rounded-t-xl transition-all duration-200">
                            VII. Training & References
                        </button>
                    </nav>
                </div>

                <form action="{{ route('pds.update') }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <!-- Section I & II: Personal & Family -->
                    <div x-show="tab === 'personal'" class="space-y-10 animate-fade-in">
                        <div>
                            <div class="flex items-center gap-3 mb-6 border-b-2 border-indigo-700 pb-2">
                                <span class="bg-indigo-700 text-white px-2 py-0.5 rounded text-[10px] font-black tracking-widest">I</span>
                                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Personal Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-1">
                                    <x-input-label for="surname" value="Surname" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" />
                                    <x-text-input id="surname" class="block w-full text-sm font-bold border-gray-100" type="text" name="personal[surname]" :value="old('personal.surname', $employee->pdsPersonal?->surname ?? $employee->lastname)" />
                                </div>
                                <div class="space-y-1">
                                    <x-input-label for="firstname" value="First Name" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" />
                                    <x-text-input id="firstname" class="block w-full text-sm font-bold border-gray-100" type="text" name="personal[firstname]" :value="old('personal.firstname', $employee->pdsPersonal?->firstname ?? $employee->firstname)" />
                                </div>
                                <div class="space-y-1">
                                    <x-input-label for="middlename" value="Middle Name" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" />
                                    <x-text-input id="middlename" class="block w-full text-sm font-bold border-gray-100" type="text" name="personal[middlename]" :value="old('personal.middlename', $employee->pdsPersonal?->middlename ?? $employee->middlename)" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                                <div class="space-y-1">
                                    <x-input-label value="Date of Birth" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" />
                                    <x-text-input class="block w-full text-sm font-bold border-gray-100" type="date" name="personal[date_of_birth]" :value="old('personal.date_of_birth', $employee->pdsPersonal?->date_of_birth)" />
                                </div>
                                <div class="space-y-1">
                                    <x-input-label value="Place of Birth" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" />
                                    <x-text-input class="block w-full text-sm font-bold border-gray-100" type="text" name="personal[place_of_birth]" :value="old('personal.place_of_birth', $employee->pdsPersonal?->place_of_birth)" />
                                </div>
                                <div class="space-y-1">
                                    <x-input-label value="Sex" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" />
                                    <select name="personal[sex]" class="mt-1 block w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold">
                                        <option value="">Select Sex</option>
                                        <option value="Male" {{ old('personal.sex', $employee->pdsPersonal?->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('personal.sex', $employee->pdsPersonal?->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <x-input-label value="Civil Status" class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em]" />
                                    <x-text-input class="block w-full text-sm font-bold border-gray-100" type="text" name="personal[civil_status]" :value="old('personal.civil_status', $employee->pdsPersonal?->civil_status)" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-3 mb-6 border-b-2 border-indigo-700 pb-2">
                                <span class="bg-indigo-700 text-white px-2 py-0.5 rounded text-[10px] font-black tracking-widest">II</span>
                                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Family Background</h3>
                            </div>
                            
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

                    <!-- Section III & IV: Education & Eligibility -->
                    <div x-show="tab === 'education'" class="space-y-12 animate-fade-in">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-indigo-700 pb-2 mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="bg-indigo-700 text-white px-2 py-0.5 rounded text-[10px] font-black tracking-widest">III</span>
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Educational Background</h3>
                                </div>
                                <button type="button" @click="education.push({level: '', school_name: '', course: '', period_from: '', period_to: '', highest_level: '', year_graduated: '', honors: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Row</button>
                            </div>
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

                        <div>
                            <div class="flex justify-between items-center border-b-2 border-indigo-700 pb-2 mb-6 mt-12">
                                <div class="flex items-center gap-3">
                                    <span class="bg-indigo-700 text-white px-2 py-0.5 rounded text-[10px] font-black tracking-widest">IV</span>
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Civil Service Eligibility</h3>
                                </div>
                                <button type="button" @click="eligibility.push({title: '', rating: '', date_of_exam: '', place_of_exam: '', license_number: '', license_validity: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Eligibility</button>
                            </div>
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

                    <!-- Section V & VI: Work & Voluntary -->
                    <div x-show="tab === 'work'" class="space-y-12 animate-fade-in">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-indigo-700 pb-2 mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="bg-indigo-700 text-white px-2 py-0.5 rounded text-[10px] font-black tracking-widest">V</span>
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Work Experience</h3>
                                </div>
                                <button type="button" @click="work.push({date_from: '', date_to: '', position_title: '', company: '', monthly_salary: '', salary_grade: '', appointment_status: '', is_gov_service: 0})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Work</button>
                            </div>
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

                    <!-- Section VII, VIII, IX: Training, Others, References -->
                    <div x-show="tab === 'others'" class="space-y-12 animate-fade-in">
                         <div>
                            <div class="flex justify-between items-center border-b-2 border-indigo-700 pb-2 mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="bg-indigo-700 text-white px-2 py-0.5 rounded text-[10px] font-black tracking-widest">VII</span>
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">L&D / Training Interventions</h3>
                                </div>
                                <button type="button" @click="training.push({title: '', date_from: '', date_to: '', number_of_hours: '', type: '', conducted_by: ''})" class="text-[9px] font-black uppercase tracking-[0.1em] bg-indigo-700 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-800 transition">+ Add Training</button>
                            </div>
                            <div class="bg-white border-2 border-gray-50 rounded-2xl overflow-hidden shadow-sm">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-left text-[9px] font-black uppercase text-gray-400 bg-gray-50">
                                            <th class="px-4 py-3">Title of Program</th>
                                            <th class="px-4 py-3">From</th>
                                            <th class="px-4 py-3">To</th>
                                            <th class="px-4 py-3">Hours</th>
                                            <th class="px-4 py-3 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 font-bold">
                                        <template x-for="(t, index) in training" :key="index">
                                            <tr>
                                                <td class="p-1"><x-text-input class="w-full text-[10px] font-bold border-transparent px-3" x-model="t.title" ::name="`training[${index}][title]`" /></td>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="t.date_from" ::name="`training[${index}][date_from]`" /></td>
                                                <td class="p-1"><x-text-input type="date" class="w-full text-[10px] font-bold border-transparent px-3" x-model="t.date_to" ::name="`training[${index}][date_to]`" /></td>
                                                <td class="p-1"><x-text-input type="number" class="w-full text-[10px] font-bold border-transparent px-3" x-model="t.number_of_hours" ::name="`training[${index}][number_of_hours]`" /></td>
                                                <td class="p-1 text-center"><button type="button" @click="training.splice(index, 1)" class="text-red-400 hover:text-red-600">&times;</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-3 mb-6 border-b-2 border-indigo-700 pb-2">
                                <span class="bg-indigo-700 text-white px-2 py-0.5 rounded text-[10px] font-black tracking-widest">IX</span>
                                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">References</h3>
                            </div>
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

                    <div class="mt-12 flex justify-end gap-3 border-t-2 border-gray-50 pt-8">
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

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>
