<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            {{ __('Personal Data Sheet (CS Form No. 212)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">PDS Overview</h3>
                            <p class="text-gray-600 dark:text-gray-400">Manage your official Civil Service Personal Data Sheet.</p>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('pds.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update PDS
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 dark:bg-green-900 dark:text-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Profile Summary Card -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl border border-gray-100 dark:border-gray-600 shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($employee->firstname, 0, 1) }}{{ substr($employee->lastname, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $employee->firstname }} {{ $employee->lastname }}</h4>
                                    <p class="text-blue-600 dark:text-blue-400 font-medium">{{ ucfirst($employee->role) }}</p>
                                </div>
                            </div>
                            <hr class="my-4 border-gray-200 dark:border-gray-600">
                            <div class="space-y-3 mt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Position:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $employee->pdsWorkExperiences->firstWhere('date_to', null)?->position_title ?? 'Not Set' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Employee No:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $employee->pdsPersonal?->agency_employee_no ?? '---' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Email:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $employee->pdsPersonal?->email_address ?? $employee->user->email }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Completion Status -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Form Completion</h4>
                            
                            @php
                                $sections = [
                                    ['label' => 'Personal Information', 'filled' => (bool)$employee->pdsPersonal],
                                    ['label' => 'Family Background', 'filled' => (bool)$employee->pdsFamily],
                                    ['label' => 'Educational Background', 'filled' => $employee->pdsEducation->count() > 0],
                                    ['label' => 'Civil Service Eligibility', 'filled' => $employee->pdsEligibilities->count() > 0],
                                    ['label' => 'Work Experience', 'filled' => $employee->pdsWorkExperiences->count() > 0],
                                    ['label' => 'Questionnaire', 'filled' => (bool)$employee->pdsQuestionnaire],
                                    ['label' => 'References', 'filled' => $employee->pdsReferences->count() >= 3],
                                ];
                                $completedCount = collect($sections)->where('filled', true)->count();
                                $percentage = round(($completedCount / count($sections)) * 100);
                            @endphp

                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-4 mb-2">
                                <div class="bg-blue-600 h-4 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ $percentage }}% Complete ({{ $completedCount }} of {{ count($sections) }} core sections filled)</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($sections as $section)
                                    <div class="flex items-center gap-2 p-3 rounded-lg {{ $section['filled'] ? 'bg-green-50 dark:bg-green-900/20 text-green-700' : 'bg-gray-50 dark:bg-gray-700/50 text-gray-500' }}">
                                        @if($section['filled'])
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                        <span class="text-sm font-medium">{{ $section['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
