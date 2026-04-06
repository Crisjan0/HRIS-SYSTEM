<x-app-layout>
    <x-slot name="title">{{ __('Personal Data Sheet (CS Form No. 212)') }}</x-slot>

    <div class="py-8 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content Area -->
            <div class="bg-white border-2 border-gray-100 rounded-2xl overflow-hidden shadow-lg shadow-gray-200/40">
                <div class="p-8">
                    <!-- Top Ribbon/Header -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b-2 border-gray-50 gap-4">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">Employee PDS Record</h3>
                            <p class="text-sm text-gray-500 font-medium italic">Civil Service Records Management Dashboard.</p>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('pds.edit') }}"
                                class="inline-flex items-center px-6 py-2.5 bg-indigo-700 border-2 border-indigo-800 rounded-xl font-bold text-sm text-white shadow-lg shadow-indigo-100 hover:bg-indigo-800 active:scale-95 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update Information
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-900 rounded-xl flex items-center shadow-sm">
                            <svg class="w-6 h-6 mr-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Left Column: Profile Summary Card -->
                        <div class="lg:col-span-4">
                            <div class="bg-gray-50/50 border-2 border-gray-100 p-6 rounded-2xl shadow-sm">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-20 h-20 bg-indigo-700 rounded-2xl flex items-center justify-center text-white text-3xl font-black shadow-xl mb-4">
                                        {{ substr($employee->firstname, 0, 1) }}{{ substr($employee->lastname, 0, 1) }}
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="text-xl font-black text-gray-900">
                                            {{ $employee->firstname }} {{ $employee->lastname }}
                                        </h4>
                                        <p class="inline-block px-3 py-1 bg-indigo-100 text-indigo-900 text-[10px] font-black uppercase tracking-widest rounded-full">
                                            {{ ucfirst($employee->role) }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-8 pt-8 border-t border-gray-200/60 space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest mb-0.5">Employee ID</p>
                                            <p class="text-base font-black text-gray-800">{{ $employee->id ?? '---' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest mb-0.5">Primary Email</p>
                                            <p class="text-sm font-bold text-gray-800 truncate tracking-tight">{{ $employee->pdsPersonal?->email_address ?? $employee->user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Status & Progress -->
                        <div class="lg:col-span-8 space-y-8">
                            <div class="bg-gray-50/50 border-2 border-gray-100 p-6 rounded-2xl shadow-sm">
                                @php
                                    $sections = [
                                        ['label' => 'Personal Information', 'filled' => (bool) $employee->pdsPersonal, 'icon' => 'user'],
                                        ['label' => 'Family Background', 'filled' => (bool) $employee->pdsFamily, 'icon' => 'users'],
                                        ['label' => 'Educational Background', 'filled' => $employee->pdsEducation->count() > 0, 'icon' => 'academic-cap'],
                                        ['label' => 'Civil Service Eligibility', 'filled' => $employee->pdsEligibilities->count() > 0, 'icon' => 'badge-check'],
                                        ['label' => 'Work Experience', 'filled' => $employee->pdsWorkExperiences->count() > 0, 'icon' => 'briefcase'],
                                        ['label' => 'Questionnaire', 'filled' => (bool) $employee->pdsQuestionnaire, 'icon' => 'question-mark-circle'],
                                        ['label' => 'References', 'filled' => $employee->pdsReferences->count() >= 3, 'icon' => 'identification'],
                                    ];
                                    $completedCount = collect($sections)->where('filled', true)->count();
                                    $totalSections = count($sections);
                                    $percentage = $totalSections > 0 ? round(($completedCount / $totalSections) * 100) : 0;
                                @endphp

                                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-2">
                                    <div>
                                        <h4 class="text-lg font-black text-gray-900">Completion Status</h4>
                                        <p class="text-sm text-gray-500 font-medium italic">Civil Service record sections compliance.</p>
                                    </div>
                                    <div class="bg-indigo-700 text-white px-5 py-2 rounded-xl shadow-md border-b-2 border-indigo-900 leading-none">
                                        <span class="text-2xl font-black">{{ $percentage }}%</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="w-full bg-white border-2 border-gray-50 rounded-2xl h-4 mb-8 overflow-hidden shadow-inner flex items-center p-1">
                                    <div class="bg-indigo-700 h-full rounded-xl transition-all duration-1000 ease-out shadow-sm"
                                        style="width: {{ $percentage }}%">
                                    </div>
                                </div>

                                <!-- Section Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($sections as $section)
                                        <div class="flex items-center p-4 bg-white rounded-xl border border-gray-100 shadow-sm transition-all duration-200">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center mr-4 {{ $section['filled'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
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
                                            <div class="overflow-hidden">
                                                <span class="block text-sm font-black truncate tracking-tight {{ $section['filled'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $section['label'] }}</span>
                                                <div class="flex items-center gap-1.5 leading-none mt-0.5">
                                                    <div class="w-1.5 h-1.5 rounded-full {{ $section['filled'] ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                                                    <span class="text-[9px] font-black uppercase tracking-widest {{ $section['filled'] ? 'text-emerald-600' : 'text-gray-400' }}">
                                                        {{ $section['filled'] ? 'Saved' : 'Blank' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>