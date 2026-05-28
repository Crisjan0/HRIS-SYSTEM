<x-app-layout>
    <x-slot name="title">{{ __('Personal Information') }}</x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-10 flex flex-col items-center gap-6 relative">
                    <!-- Background Design -->
                    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>
                    
                    <div class="relative group mt-10">
                        <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center text-indigo-600 text-4xl font-black shadow-xl uppercase overflow-hidden border-4 border-white">
                            @if($employee->profile_picture)
                                <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                            @else
                                {{ substr($employee->firstname, 0, 1) }}{{ substr($employee->lastname, 0, 1) }}
                            @endif
                        </div>
                        <label for="profile_picture_upload" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity" title="{{ __('Upload Profile Picture') }}">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </label>
                        <form id="profile_picture_form" action="{{ route('employees.profile-picture', $employee) }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="profile_picture_upload" name="profile_picture" accept="image/*" onchange="document.getElementById('profile_picture_form').submit()">
                        </form>
                    </div>

                    <div class="text-center w-full mt-2">
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                            {{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->middlename }}
                        </h1>
                        <p class="text-gray-500 mt-1 mb-8">{{ __('Your Personal Profile') }}</p>
                        
                        <div class="flex flex-col gap-3 w-full max-w-md mx-auto">
                            <!-- Position -->
                            <div class="flex items-center justify-between w-full p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-indigo-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Position') }}</span>
                                </div>
                                <span class="text-sm font-black text-indigo-700 bg-indigo-100 px-3 py-1 rounded-lg uppercase tracking-widest">{{ $employee->role }}</span>
                            </div>

                            <!-- Email -->
                            <div class="flex items-center justify-between w-full p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-indigo-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Email') }}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $employee->pdsPersonal?->email_address ?? ($employee->user?->email ?? 'N/A') }}</span>
                            </div>

                            <!-- Contact Number -->
                            <div class="flex items-center justify-between w-full p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-indigo-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Contact Number') }}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $employee->contact_number ?? ($employee->pdsPersonal?->mobile_no ?? 'N/A') }}</span>
                            </div>
                        </div>

                        <div class="mt-8 pb-4">
                            <a href="{{ route('personal-information.edit') }}" class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 border border-transparent rounded-xl font-black text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-200 hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                {{ __('Update Profile') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
