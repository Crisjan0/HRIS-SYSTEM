<x-app-layout>
    <x-slot name="title">{{ __('Edit Leave Type') }}</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8">
                    <form method="POST" action="{{ route('leave-types.update', $leaveType) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Leave Type Name')" class="text-xs font-black uppercase text-gray-500 tracking-widest" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full text-sm font-bold border-gray-100" :value="old('name', $leaveType->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Days Per Year -->
                        <div>
                            <x-input-label for="days_per_year" :value="__('Days Per Year (Leave Credit)')" class="text-xs font-black uppercase text-gray-500 tracking-widest" />
                            <x-text-input id="days_per_year" name="days_per_year" type="number" class="mt-1 block w-full text-sm font-bold border-gray-100" :value="old('days_per_year', $leaveType->days_per_year)" placeholder="Leave empty for unlimited" />
                            <x-input-error class="mt-2" :messages="$errors->get('days_per_year')" />
                            <p class="mt-1 text-[10px] text-gray-400 font-medium italic">{{ __('Leave blank or enter 0 if this type of leave has no specific annual limit.') }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description / Remarks')" class="text-xs font-black uppercase text-gray-500 tracking-widest" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-medium" rows="4">{{ old('description', $leaveType->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Is Active -->
                        <div class="flex items-center gap-2 py-4">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $leaveType->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="is_active" class="text-sm font-bold text-gray-700">{{ __('Mark as Active') }}</label>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6">
                            <a href="{{ route('leave-types.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 border-none shadow-lg shadow-indigo-200 translate-y-0 hover:-translate-y-0.5 transition-all duration-200">
                                {{ __('Update Leave Type') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
