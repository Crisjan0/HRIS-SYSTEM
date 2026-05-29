<x-app-layout>
    <x-slot name="title">{{ __('Employee Details') }} - {{ $employee->firstname }} {{ $employee->lastname }}</x-slot>

    <div class="py-8 bg-gray-50 min-h-screen" x-data="{ tab: 'pds', reviewModalOpen: false, currentSection: '', sectionData: null, reviewStatus: 'pending', reviewRemarks: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 sm:p-8 flex flex-col md:flex-row items-center gap-6">
                    <div class="relative group">
                        <div class="w-24 h-24 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-3xl font-black shadow-xl uppercase overflow-hidden">
                            @if($employee->profile_picture)
                                <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                            @else
                                {{ substr($employee->firstname, 0, 1) }}{{ substr($employee->lastname, 0, 1) }}
                            @endif
                        </div>
                        @if(in_array(auth()->user()->role, ['admin', 'hrstaff', 'director', 'chief', 'regionaldirector', 'regional director']) || auth()->user()->employee?->id === $employee->id)
                            <label for="profile_picture_upload" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-2xl opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity" title="{{ __('Upload Profile Picture') }}">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </label>
                            <form id="profile_picture_form" action="{{ route('employees.profile-picture', $employee) }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" id="profile_picture_upload" name="profile_picture" accept="image/*" onchange="document.getElementById('profile_picture_form').submit()">
                            </form>
                        @endif
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                            {{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->middlename }}
                        </h1>
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-2">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-black uppercase tracking-widest rounded-full">
                                {{ $employee->role }}
                            </span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-black tracking-widest rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $employee->pdsPersonal?->email_address ?? ($employee->user?->email ?? 'N/A') }}
                            </span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-black tracking-widest rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $employee->contact_number ?? ($employee->pdsPersonal?->mobile_no ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('employees.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all active:scale-95">
                            {{ __('Back to List') }}
                        </a>
                        <a href="{{ route('employees.edit', $employee) }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95">
                            {{ __('Edit Record') }}
                        </a>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex border-t border-gray-100 px-6 sm:px-8 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <button @click="tab = 'pds'" :class="tab === 'pds' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-6 py-4 border-b-2 font-bold text-sm transition-all focus:outline-none">
                        {{ __('Personal Data Sheet (PDS)') }}
                    </button>
                    <button @click="tab = 'saln'" :class="tab === 'saln' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-6 py-4 border-b-2 font-bold text-sm transition-all focus:outline-none">
                        {{ __('SALN') }}
                    </button>
                    <button @click="tab = 'ilpd'" :class="tab === 'ilpd' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-6 py-4 border-b-2 font-bold text-sm transition-all focus:outline-none">
                        {{ __('ILPD') }}
                    </button>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="space-y-6">
                <!-- PDS Tab -->
                <div x-show="tab === 'pds'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Left Column: Quick Info -->
                        <div class="lg:col-span-4 space-y-6">
                            <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
                                <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">{{ __('Contact Information') }}</h4>
                                
                                <div class="space-y-6">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">{{ __('Email Address') }}</p>
                                            <p class="text-sm font-black text-gray-900 truncate">{{ $employee->pdsPersonal?->email_address ?? ($employee->user?->email ?? '---') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 002-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">{{ __('Mobile Number') }}</p>
                                            <p class="text-sm font-black text-gray-900">{{ $employee->pdsPersonal?->mobile_no ?? '---' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">{{ __('Residential Address') }}</p>
                                            <p class="text-sm font-black text-gray-900 leading-tight">
                                                @if($employee->pdsPersonal?->res_house_no)
                                                    {{ $employee->pdsPersonal->res_house_no }}, {{ $employee->pdsPersonal->res_street }}, {{ $employee->pdsPersonal->res_barangay }}, {{ $employee->pdsPersonal->res_city }}, {{ $employee->pdsPersonal->res_province }}
                                                @else
                                                    ---
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: PDS Sections Completion -->
                        <div class="lg:col-span-8 space-y-6">
                            <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
                                @php
                                    $sections = [
                                        ['label' => 'Personal Information', 'filled' => (bool) $employee->pdsPersonal, 'icon' => 'user', 'data' => $employee->pdsPersonal],
                                        ['label' => 'Family Background', 'filled' => (bool) $employee->pdsFamily, 'icon' => 'users', 'data' => $employee->pdsFamily],
                                        ['label' => 'Educational Background', 'filled' => $employee->pdsEducation->count() > 0, 'icon' => 'academic-cap', 'data' => $employee->pdsEducation],
                                        ['label' => 'Civil Service Eligibility', 'filled' => $employee->pdsEligibilities->count() > 0, 'icon' => 'badge-check', 'data' => $employee->pdsEligibilities],
                                        ['label' => 'Work Experience', 'filled' => $employee->pdsWorkExperiences->count() > 0, 'icon' => 'briefcase', 'data' => $employee->pdsWorkExperiences],
                                        ['label' => 'Questionnaire', 'filled' => (bool) $employee->pdsQuestionnaire, 'icon' => 'question-mark-circle', 'data' => $employee->pdsQuestionnaire],
                                        ['label' => 'References', 'filled' => $employee->pdsReferences->count() >= 3, 'icon' => 'identification', 'data' => $employee->pdsReferences],
                                    ];
                                    $completedCount = collect($sections)->where('filled', true)->count();
                                    $totalSections = count($sections);
                                    $percentage = $totalSections > 0 ? round(($completedCount / $totalSections) * 100) : 0;
                                @endphp

                                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-2">
                                    <div>
                                        <h4 class="text-lg font-black text-gray-900">PDS Completion Status</h4>
                                        <p class="text-sm text-gray-500 font-medium italic">Civil Service record sections compliance.</p>
                                    </div>
                                    <div class="bg-indigo-600 text-white px-5 py-2 rounded-xl shadow-md leading-none">
                                        <span class="text-2xl font-black">{{ $percentage }}%</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-100 rounded-full h-3 mb-8 overflow-hidden shadow-inner">
                                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000 ease-out"
                                        style="width: {{ $percentage }}%">
                                    </div>
                                </div>

                                <!-- Section Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($sections as $section)
                                        @php
                                            $review = $employee->pdsSectionReviews->where('section_name', $section['label'])->first();
                                            $reviewStatus = $review ? $review->status : 'pending';
                                            $reviewRemarks = $review ? $review->remarks : '';
                                        @endphp
                                        <button @click="reviewModalOpen = true; currentSection = '{{ $section['label'] }}'; sectionData = {{ json_encode($section['data']) }}; reviewStatus = '{{ $reviewStatus }}'; reviewRemarks = '{{ addslashes($reviewRemarks) }}'" class="group flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100 shadow-sm transition-all duration-200 hover:border-indigo-300 hover:shadow-md cursor-pointer relative overflow-hidden text-left focus:outline-none">
                                            <!-- Hover indicator bar -->
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center mr-4 transition-transform group-hover:scale-110 {{ $section['filled'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-400 group-hover:bg-indigo-50 group-hover:text-indigo-500' }}">
                                                @if($section['filled'])
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden flex-1">
                                                <span class="block text-sm font-black truncate tracking-tight transition-colors {{ $section['filled'] ? 'text-gray-900 group-hover:text-emerald-700' : 'text-gray-400 group-hover:text-indigo-600' }}">{{ $section['label'] }}</span>
                                                <div class="flex items-center justify-between mt-0.5">
                                                    <div class="flex items-center gap-1.5 leading-none">
                                                        <div class="w-1.5 h-1.5 rounded-full {{ $section['filled'] ? 'bg-emerald-500' : 'bg-gray-300 group-hover:bg-indigo-400' }}"></div>
                                                        <span class="text-[9px] font-black uppercase tracking-widest transition-colors {{ $section['filled'] ? 'text-emerald-600' : 'text-gray-400 group-hover:text-indigo-500' }}">
                                                            {{ $section['filled'] ? 'Saved' : 'Blank' }}
                                                        </span>
                                                        @if($reviewStatus === 'approved')
                                                            <span class="ml-2 text-[9px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-100 px-1 rounded">Approved</span>
                                                        @elseif($reviewStatus === 'rejected')
                                                            <span class="ml-2 text-[9px] font-black uppercase tracking-widest text-red-600 bg-red-100 px-1 rounded">Rejected</span>
                                                        @endif
                                                    </div>
                                                    <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>

                                <div class="mt-8 flex justify-center">
                                    <a href="{{ route('pds.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold flex items-center gap-2">
                                        {{ __('View Full PDS Record') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SALN Tab -->
                <div x-show="tab === 'saln'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('SALN Records') }}</h3>
                        <p class="text-gray-500 mt-2 max-w-md mx-auto">{{ __('Statement of Assets, Liabilities, and Net Worth records will be available here once the module is fully integrated.') }}</p>
                        <div class="mt-8">
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 text-xs font-black uppercase tracking-widest rounded-full border border-yellow-200">
                                {{ __('Feature Coming Soon') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ILPD Tab -->
                <div x-show="tab === 'ilpd'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('ILPD Records') }}</h3>
                        <p class="text-gray-500 mt-2 max-w-md mx-auto">{{ __('Individual Learning and Development Plan records will be available here once the module is fully integrated.') }}</p>
                        <div class="mt-8">
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 text-xs font-black uppercase tracking-widest rounded-full border border-yellow-200">
                                {{ __('Feature Coming Soon') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- PDS Review Modal -->
        <div x-show="reviewModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="reviewModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="reviewModalOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="reviewModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-black text-gray-900" id="modal-title" x-text="'Review ' + currentSection"></h3>
                                
                                <!-- Data Viewer -->
                                <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm">
                                    <h4 class="font-bold text-gray-700 mb-2">Section Data:</h4>
                                    <template x-if="sectionData">
                                        <div class="space-y-1">
                                            <template x-if="Array.isArray(sectionData) && sectionData.length > 0">
                                                <div>
                                                    <template x-for="(item, index) in sectionData" :key="index">
                                                        <div class="mb-4 pb-2 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0">
                                                            <div class="font-bold text-gray-500 text-xs mb-1 uppercase tracking-wider" x-text="'Entry ' + (index + 1)"></div>
                                                            <template x-for="(value, key) in item" :key="key">
                                                                <div class="grid grid-cols-3 gap-2 py-1" x-show="key !== 'id' && key !== 'employee_id' && key !== 'created_at' && key !== 'updated_at'">
                                                                    <div class="col-span-1 font-bold text-gray-600 capitalize truncate" x-text="key.replace(/_/g, ' ')"></div>
                                                                    <div class="col-span-2 text-gray-900" x-text="value === null ? '---' : value"></div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="!Array.isArray(sectionData)">
                                                <div>
                                                    <template x-for="(value, key) in sectionData" :key="key">
                                                        <div class="grid grid-cols-3 gap-2 py-1 border-b border-gray-100 last:border-0" x-show="key !== 'id' && key !== 'employee_id' && key !== 'created_at' && key !== 'updated_at'">
                                                            <div class="col-span-1 font-bold text-gray-600 capitalize truncate" x-text="key.replace(/_/g, ' ')"></div>
                                                            <div class="col-span-2 text-gray-900" x-text="value === null || value === '' ? '---' : value"></div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!sectionData || (Array.isArray(sectionData) && sectionData.length === 0)">
                                        <div class="text-gray-500 italic">No data inputted for this section yet.</div>
                                    </template>
                                </div>

                                <!-- Review Form -->
                                <form id="review-form" action="{{ route('pds-reviews.store', $employee) }}" method="POST" class="mt-6 space-y-4">
                                    @csrf
                                    <input type="hidden" name="section_name" x-model="currentSection">
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                        <select name="status" x-model="reviewStatus" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approve</option>
                                            <option value="rejected">Reject</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Remarks</label>
                                        <textarea name="remarks" x-model="reviewRemarks" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter remarks (optional)..."></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" form="review-form" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Review
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="reviewModalOpen = false">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
