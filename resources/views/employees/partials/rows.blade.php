@forelse($employees as $employee)
    @php
        $roleLabels = [
            'employee' => 'Employee',
            'hrstaff' => 'HR Admin',
            'recordofficer' => 'Record Officer',
            'chief' => 'Chief',
            'regionaldirector' => 'Regional Director',
            'admin' => 'Admin',
        ];
        $accountRole = strtolower((string) ($employee->account_role ?? ''));
    @endphp
    <tr class="hover:bg-gray-50 transition-colors duration-200">
        <td class="px-4 py-4 text-sm font-medium text-gray-900">
            <div class="truncate">{{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->middlename ?? '-' }}</div>
        </td>
        <td class="px-4 py-4 text-sm text-gray-500">
            @if($employee->rfid_number)
                <span class="px-2 py-1 inline-flex text-xs font-medium rounded-md bg-blue-100 text-blue-800">{{ $employee->rfid_number }}</span>
            @else
                <span class="text-gray-400 italic text-xs">{{ __('No RFID') }}</span>
            @endif
        </td>
        <td class="px-4 py-4 text-sm text-gray-600">
            <div class="truncate">{{ $employee->division ?? '-' }}</div>
        </td>
        <td class="px-4 py-4">
            <span class="max-w-full truncate px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                {{ $roleLabels[$accountRole] ?? 'Unassigned' }}
            </span>
        </td>
        <td class="px-4 py-4 text-right text-sm font-medium">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('employees.show', $employee) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900 transition-colors duration-200" title="{{ __('View') }}" aria-label="{{ __('View') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="sr-only">{{ __('View') }}</span>
                </a>
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-700 hover:bg-blue-50 hover:text-blue-900 transition-colors duration-200" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M16.862 4.487l1.651-1.651a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M19.5 7.125L16.875 4.5" />
                    </svg>
                    <span class="sr-only">{{ __('Edit') }}</span>
                </a>
                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Archive this employee and disable their login access?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-700 hover:bg-red-50 hover:text-red-900 transition-colors duration-200" title="{{ __('Archive') }}" aria-label="{{ __('Archive') }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
     stroke-linecap="round"
    stroke-linejoin="round">
                              <path d="M4 7.5h16v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" />

    <!-- Box cover -->
    <path d="M3 4.5h18v3H3z" />

    <!-- Down arrow -->
    <path d="M12 10v6" />
    <path d="m9.5 13.5 2.5 2.5 2.5-2.5" />
                        </svg>
                        <span class="sr-only">{{ __('Archive') }}</span>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
            <div class="flex flex-col items-center">
                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <p class="text-lg font-medium">{{ __('No employees found') }}</p>
                <p class="text-sm mt-1 text-gray-400">{{ __('Try adjusting the search or division filter.') }}</p>
            </div>
        </td>
    </tr>
@endforelse

