<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-indigo-700">
            {{ __('Edit Leave Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 ring-1 ring-black/5">
                <div class="p-8">
                    <form method="POST" action="{{ route('leaves.update', $leaf) }}" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Leave Type -->
                        <div>
                            <x-input-label for="leave_type_id" :value="__('Type of Leave')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                            <select id="leave_type_id" name="leave_type_id" class="mt-1 block w-full text-sm font-bold border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm py-3 px-4 bg-gray-50/50" required>
                                @foreach($leaveTypes as $type)
                                    @php
                                        $balance = $credits[$type->id]->balance ?? 0;
                                    @endphp
                                    <option value="{{ $type->id }}" @selected(old('leave_type_id', $leaf->leave_type_id) == $type->id)>
                                        {{ $type->name }} ({{ number_format($balance, 1) }} {{ __('days available') }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('leave_type_id')" />
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="start_date" :value="__('Start Date')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full text-sm font-bold border-gray-100 py-3 px-4 bg-gray-50/50" :value="old('start_date', $leaf->start_date)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('End Date')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full text-sm font-bold border-gray-100 py-3 px-4 bg-gray-50/50" :value="old('end_date', $leaf->end_date)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                            </div>
                        </div>

                        <!-- Reason -->
                        <div>
                            <x-input-label for="reason" :value="__('Reason for Leave')" class="text-[10px] font-black uppercase text-indigo-600 tracking-[0.2em] mb-2" />
                            <textarea id="reason" name="reason" class="mt-1 block w-full border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3 px-4 bg-gray-50/50" rows="5" required>{{ old('reason', $leaf->reason) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                        </div>

                        <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-50">
                            <a href="{{ route('leaves.index') }}" class="text-sm font-black text-gray-400 hover:text-indigo-600 transition-colors duration-300 uppercase tracking-widest">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 border-none shadow-xl shadow-indigo-200/50 px-8 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-1">
                                {{ __('Update Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
