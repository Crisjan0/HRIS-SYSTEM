<x-app-layout>
    <x-slot name="title">{{ __('Pending Leave Applications') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @include('leaves._manage-tabs')

            <div class="space-y-4">
                @forelse($leaves as $leaf)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 leading-tight">
                                {{ $leaf->employee->firstname }} {{ $leaf->employee->lastname }}
                            </h3>
                            <div class="text-sm text-gray-600 mt-1">
                                <span class="font-medium text-indigo-600">{{ $leaf->leaveType->name }}</span>
                                <span class="mx-1 text-gray-400">from</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($leaf->start_date)->format('M d, Y') }}</span>
                                <span class="mx-1 text-gray-400">to</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($leaf->end_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Status:</span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-orange-500 bg-orange-50 px-2 py-0.5 rounded">PENDING</span>

                                @if($leaf->attachment_path)
                                    <a href="{{ asset('storage/' . $leaf->attachment_path) }}" target="_blank" class="flex items-center gap-1 text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        Attachment
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('leave-applications.show', $leaf->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shadow-lg hover:-translate-y-0.5">
                                REVIEW
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                        <div class="text-gray-400 mb-2">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 italic font-medium">
                            {{ __('No pending leave applications found.') }}
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
