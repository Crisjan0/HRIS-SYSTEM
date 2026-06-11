<x-app-layout>
    <x-slot name="title">{{ __('Manage Leave Types') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @include('leaves._manage-tabs')

            <!-- Header Actions -->
            <div class="flex justify-end mb-6">
                <a href="{{ route('leave-types.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200 hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Leave Type
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Days/Year') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Description') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Legal Basis') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($leaveTypes as $type)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $type->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $type->days_per_year ?? __('Unlimited') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $type->description ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-indigo-600 max-w-xs truncate italic">{{ $type->legal_basis ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($type->is_active)
                                                <span class="px-2 inline-flex text-[10px] leading-5 font-black rounded-full bg-green-100 text-green-800 uppercase tracking-widest">
                                                    {{ __('Active') }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-[10px] leading-5 font-black rounded-full bg-gray-100 text-gray-800 uppercase tracking-widest">
                                                    {{ __('Inactive') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('leave-types.edit', $type) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-700 hover:bg-blue-50 hover:text-blue-900 transition-colors duration-200" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.651-1.651a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 7.125L16.875 4.5" />
                                                </svg>
                                                <span class="sr-only">{{ __('Edit') }}</span>
                                            </a>
                                            <form action="{{ route('leave-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this leave type?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-600 hover:bg-red-50 hover:text-red-900 transition-colors duration-200" title="{{ __('Delete') }}" aria-label="{{ __('Delete') }}">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-9 0V5.25A1.25 1.25 0 0110.25 4h3.5A1.25 1.25 0 0115 5.25V7m-7 0l.75 12A2 2 0 0010.75 21h2.5a2 2 0 002-1.875L16 7" />
                                                    </svg>
                                                    <span class="sr-only">{{ __('Delete') }}</span>
                                                </button>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('leave-types.edit', $type) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">        <i class="fa-solid fa-pen-to-square"></i>
</a>
                                            <form action="{{ route('leave-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this leave type?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                <p class="text-lg font-medium">{{ __('No leave types found') }}</p>
                                                <p class="text-sm mt-1 text-gray-400">{{ __('Get started by adding a new leave type.') }}</p>
                                            </div>
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
</x-app-layout>
