<x-app-layout>
    <x-slot name="title">{{ __('Personal Information') }}</x-slot>

    <div class="py-12 bg-slate-50 min-h-screen flex items-center justify-center sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">
            
            <!-- Main Profile Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden grid grid-cols-1 md:grid-cols-12">
                
                <!-- Left Section: Avatar & Core Identity -->
                <div class="md:col-span-5 bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800 p-8 flex flex-col items-center justify-center text-center text-white relative overflow-hidden min-h-[320px] md:min-h-[440px]">
                    <!-- Background Pattern Graphic -->
                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    
                    <div class="relative z-10 flex flex-col items-center">
                        <!-- Avatar Container -->
                        <div class="relative group">
                            <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center text-indigo-100 text-4xl font-bold shadow-2xl uppercase overflow-hidden ring-4 ring-white/20 backdrop-blur-sm transition-all duration-300 group-hover:ring-white/40">
                                @if($employee->profile_picture)
                                    <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                                @else
                                    {{ substr($employee->firstname, 0, 1) }}{{ substr($employee->lastname, 0, 1) }}
                                @endif
                            </div>
                            
                            <!-- Upload Trigger Overlay -->
                            <label for="profile_picture_upload" class="absolute inset-0 bg-slate-900/60 flex flex-col items-center justify-center rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-all duration-200 backdrop-blur-xs scale-95 group-hover:scale-100" title="{{ __('Upload Profile Picture') }}">
                                <svg class="w-6 h-6 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-[10px] text-white font-medium tracking-wide uppercase">{{ __('Change') }}</span>
                            </label>

                            <form id="profile_picture_form" action="{{ route('employees.profile-picture', $employee) }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" id="profile_picture_upload" name="profile_picture" accept="image/*" onchange="document.getElementById('profile_picture_form').submit()">
                            </form>
                        </div>

                        <!-- Employee Name Header -->
                        <h1 class="text-2xl font-bold tracking-tight text-white mt-6 leading-tight">
                            {{ $employee->firstname }} {{ $employee->lastname }}
                        </h1>
                        @if($employee->middlename)
                            <p class="text-xs text-indigo-200/70 font-medium mt-0.5 tracking-wide uppercase">{{ $employee->middlename }}</p>
                        @endif

                        <!-- Clean Position Badge -->
                        <div class="mt-4 inline-flex items-center px-3 py-1 bg-white/10 rounded-full border border-white/10 backdrop-blur-md">
                            <span class="text-xs font-semibold text-indigo-100 uppercase tracking-wider">{{ $employee->role }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Account Details List -->
                <div class="md:col-span-7 p-8 sm:p-10 flex flex-col justify-between">
                    <div>
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-slate-800">{{ __('Personal Profile') }}</h2>
                            <p class="text-sm text-slate-400 mt-1">{{ __('Verified account details and contact pathways.') }}</p>
                        </div>

                        <!-- Information Rows Container -->
                        <div class="space-y-4">
                            <!-- Position Details Row -->
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 transition-all hover:border-slate-200">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('Organizational Position') }}</span>
                                        <span class="text-sm font-semibold text-slate-700 block mt-0.5 truncate">{{ strtoupper($employee->role) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Row -->
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 transition-all hover:border-slate-200">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('Primary Email') }}</span>
                                        <span class="text-sm font-semibold text-slate-700 block mt-0.5 truncate break-all">
                                            {{ $employee->pdsPersonal?->email_address ?? ($employee->user?->email ?? __('N/A')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Number Row -->
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 transition-all hover:border-slate-200">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('Contact Number') }}</span>
                                        <span class="text-sm font-semibold text-slate-700 block mt-0.5 truncate">
                                            {{ $employee->contact_number ?? ($employee->pdsPersonal?->mobile_no ?? __('N/A')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer Area -->
                    <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end">
                        <a href="{{ route('personal-information.edit') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all shadow-md shadow-indigo-100 group">
                            <svg class="w-4 h-4 mr-2 text-indigo-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            {{ __('Update Profile') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>