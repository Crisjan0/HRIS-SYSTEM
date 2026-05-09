<x-app-layout>
    <x-slot name="title">{{ __('Manage Holidays') }}</x-slot>

    <div class="py-12" x-data="{ 
        editMode: false, 
        currentHoliday: { id: '', name: '', date: '' },
        openEdit(holiday) {
            this.currentHoliday = { ...holiday };
            this.editMode = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add Holiday Form -->
                <div class="lg:col-span-1">
                    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-100 sticky top-12">
                        <h3 class="text-xl font-black uppercase tracking-widest mb-6">{{ __('Add Holiday') }}</h3>
                        
                        <form action="{{ route('holidays.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-2">{{ __('Holiday Name') }}</label>
                                <input type="text" name="name" required
                                    class="w-full border-transparent rounded-2xl bg-indigo-500/50 text-sm font-medium focus:ring-white focus:border-white text-white placeholder-indigo-300"
                                    placeholder="e.g. Christmas Day">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-2">{{ __('Date') }}</label>
                                <input type="date" name="date" required
                                    class="w-full border-transparent rounded-2xl bg-indigo-500/50 text-sm font-medium focus:ring-white focus:border-white text-white">
                            </div>

                            <button type="submit"
                                class="w-full bg-white text-indigo-600 hover:bg-indigo-50 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg transform hover:-translate-y-1">
                                {{ __('Save Holiday') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Holidays List -->
                <div class="lg:col-span-2 space-y-6">
                    @if(session('success'))
                        <div class="p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl text-sm font-bold animate-in fade-in slide-in-from-top-4 duration-500">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-bold shadow-sm">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden text-nowrap">
                        <div class="overflow-x-auto overflow-y-auto max-h-[600px]">
                            <table class="w-full text-left">
                                <thead class="sticky top-0 bg-white z-10">
                                    <tr class="bg-gray-50/50 border-b border-gray-100">
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Date') }}</th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Holiday Name') }}</th>
                                        <th class="px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 uppercase font-black text-xs text-center">
                                    @forelse($holidays as $holiday)
                                        <tr class="hover:bg-gray-50/50 transition-colors group">
                                            <td class="px-6 py-4 text-left">
                                                <div class="text-sm font-black text-gray-900 ">
                                                    {{ $holiday->date->format('M d, Y') }}
                                                </div>
                                                <div class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">
                                                    {{ $holiday->date->format('l') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-left">
                                                <div class="text-sm font-bold text-gray-700">{{ $holiday->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 flex items-center justify-center gap-2">
                                                <!-- Edit Button -->
                                                <button 
                                                    @click="openEdit({ id: '{{ $holiday->id }}', name: '{{ addslashes($holiday->name) }}', date: '{{ $holiday->date->format('Y-m-d') }}' })"
                                                    class="p-2 text-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                                    title="Edit Holiday"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>

                                                <!-- Delete Button -->
                                                <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this holiday?')"
                                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"
                                                        title="Delete Holiday"
                                                    >
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic">
                                                {{ __('No holidays registered yet.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div 
            x-show="editMode" 
            class="fixed inset-0 z-50 overflow-y-auto" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="editMode = false">
                    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div 
                    x-show="editMode"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20"
                >
                    <div class="bg-white p-8 md:p-10">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase tracking-widest">{{ __('Update Holiday') }}</h3>
                            <button @click="editMode = false" class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form :action="'{{ route('holidays.index') }}/' + currentHoliday.id" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">{{ __('Holiday Name') }}</label>
                                <input type="text" name="name" x-model="currentHoliday.name" required
                                    class="w-full border-gray-100 rounded-2xl bg-gray-50 text-sm font-bold text-gray-900 focus:ring-indigo-600 focus:border-indigo-600 px-4 py-3">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">{{ __('Date') }}</label>
                                <input type="date" name="date" x-model="currentHoliday.date" required
                                    class="w-full border-gray-100 rounded-2xl bg-gray-50 text-sm font-bold text-gray-900 focus:ring-indigo-600 focus:border-indigo-600 px-4 py-3">
                            </div>

                            <div class="pt-4 flex gap-3">
                                <button type="submit"
                                    class="flex-1 bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-100 transform hover:-translate-y-1">
                                    {{ __('Update Holiday') }}
                                </button>
                                <button type="button" @click="editMode = false"
                                    class="px-6 py-4 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                                    {{ __('Cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
