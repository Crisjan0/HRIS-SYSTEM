<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee: ') }} {{ $employee->firstname }} {{ $employee->lastname }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8 text-gray-900 border-b border-gray-200">
                    <form action="{{ route('employees.update', $employee) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-100">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('Personal Information') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Use the employee\'s official name as per their government records.') }}</p>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="lastname" :value="__('Last Name')" />
                                    <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname', $employee->lastname)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                                </div>
                                <div>
                                    <x-input-label for="firstname" :value="__('First Name')" />
                                    <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full" :value="old('firstname', $employee->firstname)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
                                </div>
                                <div>
                                    <x-input-label for="middlename" :value="__('Middle Name')" />
                                    <x-text-input id="middlename" name="middlename" type="text" class="mt-1 block w-full" :value="old('middlename', $employee->middlename)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('middlename')" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 pt-4 border-b border-gray-100">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('Job Role & Permission') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Assign a specific role for this employee record.') }}</p>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="role" :value="__('Role')" />
                                    <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}" {{ old('role', $employee->role) == $role ? 'selected' : '' }}>{{ strtoupper($role) }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('role')" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('Account Linking') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Optionally link this record to a registered user account to grant them record access.') }}</p>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="user_id" :value="__('Linked User Account')" />
                                    <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">{{ __('None - Keep as standby record') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100">
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                {{ __('Update Employee') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
