<x-app-layout>
    <x-slot name="title">{{ __('Edit Personal Information') }}</x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-10 relative">
                    <!-- Background Design -->
                    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-indigo-500 to-indigo-600 z-0"></div>
                    
                    <div class="relative z-10 pt-10">
                        <div class="text-center mb-8">
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ __('Edit Personal Profile') }}</h1>
                            <p class="text-gray-500 mt-2">{{ __('Update your contact number and email address below.') }}</p>
                        </div>

                        <form action="{{ route('personal-information.update') }}" method="POST" class="max-w-md mx-auto space-y-6">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <x-input-label for="firstname" :value="__('First Name')" />
                                    <input type="text" name="firstname" id="firstname" value="{{ old('firstname', $employee->firstname) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <x-input-label for="lastname" :value="__('Last Name')" />
                                    <input type="text" name="lastname" id="lastname" value="{{ old('lastname', $employee->lastname) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                                </div>

                                <!-- Middle Name -->
                                <div class="md:col-span-2">
                                    <x-input-label for="middlename" :value="__('Middle Name')" />
                                    <input type="text" name="middlename" id="middlename" value="{{ old('middlename', $employee->middlename) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <x-input-error class="mt-2" :messages="$errors->get('middlename')" />
                                </div>
                            </div>

                            <!-- Position (Readonly) -->
                            <div>
                                <x-input-label :value="__('Position')" />
                                <div class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md shadow-sm sm:text-sm text-gray-500 font-medium cursor-not-allowed">
                                    {{ strtoupper($employee->role) }}
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ __('Position changes must be processed by HR.') }}</p>
                            </div>

                            <!-- Email (Readonly) -->
                            <div>
                                <x-input-label :value="__('Email Address')" />
                                <div class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md shadow-sm sm:text-sm text-gray-500 font-medium cursor-not-allowed">
                                    {{ $employee->pdsPersonal?->email_address ?? auth()->user()->email }}
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ __('To update your email address, please go to ') }} <a href="{{ route('profile.edit') }}" class="text-indigo-600 hover:underline font-bold">{{ __('Account Settings') }}</a>.</p>
                            </div>

                            <!-- Contact Number -->
                            <div>
                                <x-input-label for="contact_number" :value="__('Contact Number')" />
                                <div class="relative mt-1 rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $employee->contact_number ?? $employee->pdsPersonal?->mobile_no) }}" class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. 09123456789">
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 mt-8">
                                <a href="{{ route('personal-information.show') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-black text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-200">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
