<x-app-layout>
    <x-slot name="title">{{ __('Manage Employees') }}</x-slot>

    <div class="py-12" x-data="{ tab: 'employees', accountsTab: 'pending' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Header Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="inline-flex rounded-xl bg-white border border-gray-100 shadow-sm p-1">
                    <button type="button"
                            class="px-4 py-2 text-sm font-semibold rounded-lg transition"
                            :class="tab === 'employees' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700'"
                            @click="tab = 'employees'">
                        Employees Records
                    </button>
                    <button type="button"
                            class="px-4 py-2 text-sm font-semibold rounded-lg transition"
                            :class="tab === 'accounts' ? 'bg-amber-100 text-amber-700' : 'text-gray-500 hover:text-gray-700'"
                            @click="tab = 'accounts'">
                        Employees Account
                    </button>
                </div>

                
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100" x-show="tab === 'employees'" x-cloak>
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Employees Name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Contact Number') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('RFID Number') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Position') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Linked User') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($employees as $employee)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->middlename ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->contact_number ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($employee->rfid_number)
                                                <span class="px-2 py-1 inline-flex text-xs font-medium rounded-md bg-blue-100 text-blue-800">{{ $employee->rfid_number }}</span>
                                            @else
                                                <span class="text-gray-400 italic text-xs">{{ __('No RFID') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 uppercase">
                                                {{ $employee->position }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($employee->user)
                                                <div class="flex items-center gap-2">
                                                    <x-profile-avatar :employee="$employee" size="xs" variant="indigo" />
                                                    <div class="flex flex-col">
                                                        <span>{{ $employee->user->name }}</span>
                                                        <span class="text-xs text-gray-400">{{ $employee->user->email }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic text-xs">{{ __('Not linked') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <a href="{{ route('employees.show', $employee) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-200" >
                                                <i class="fa-solid fa-eye"></i> </a>

                                            <a href="{{ route('employees.edit', $employee) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"><i class="fa-solid fa-pen-to-square"></i> </a>

                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this employee record?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                                <p class="text-lg font-medium">{{ __('No employees found') }}</p>
                                                <p class="text-sm mt-1 text-gray-400">{{ __('Get started by adding a new employee.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'accounts'" x-cloak>
                <div class="mb-6 mt-6">
                    <div class="inline-flex rounded-xl bg-white border border-gray-100 shadow-sm p-1">
                        <button type="button"
                                class="px-4 py-2 text-sm font-semibold rounded-lg transition"
                                :class="accountsTab === 'pending' ? 'bg-amber-100 text-amber-700' : 'text-gray-500 hover:text-gray-700'"
                                @click="accountsTab = 'pending'">
                            Pending Accounts ({{ $pendingAccounts->count() }})
                        </button>
                        <button type="button"
                                class="px-4 py-2 text-sm font-semibold rounded-lg transition"
                                :class="accountsTab === 'approved' ? 'bg-green-100 text-green-700' : 'text-gray-500 hover:text-gray-700'"
                                @click="accountsTab = 'approved'">
                            Active Accounts ({{ $approvedAccounts->count() }})
                        </button>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 mb-8" x-show="accountsTab === 'pending'" x-cloak>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">{{ __('Pending Approval') }}</h2>
                                <p class="text-xs text-gray-400">{{ __('Registered accounts waiting for HR verification') }}</p>
                            </div>
                            @if($pendingAccounts->count() > 0)
                                <span class="ml-auto px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700">
                                    {{ $pendingAccounts->count() }} {{ __('pending') }}
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @forelse($pendingAccounts as $user)
                                <div class="border border-gray-100 rounded-2xl p-5 bg-white shadow-sm hover:shadow-md transition account-card" data-link="{{ $user->employee ? route('employee-accounts.show', $user) : '' }}">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <x-profile-avatar :user="$user" size="md" variant="amber" />
                                            <div>
                                                @if($user->employee)
                                                    <a href="{{ route('employee-accounts.show', $user) }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-700 hover:underline">
                                                        {{ $user->employee->lastname }}, {{ $user->employee->firstname }} {{ $user->employee->middlename ?? '' }}
                                                        @if($user->employee->suffix)
                                                            <span class="text-gray-400">{{ $user->employee->suffix }}</span>
                                                        @endif
                                                    </a>
                                                @else
                                                    <p class="text-sm font-semibold text-gray-900">-</p>
                                                @endif
                                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-semibold text-gray-500">{{ $user->created_at->format('M d, Y') }}</span>
                                    </div>

                                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-gray-600">
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-gray-400">Division</span>
                                            <span class="font-semibold">
                                                {{ $user->employee?->division ?? 'Not set' }}

                                            </span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-gray-400">Position</span>
                                            <span class="inline-flex px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 font-semibold uppercase">
                                                {{ $user->employee?->position ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-gray-400">Account Role</span>
                                            <span class="inline-flex px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 font-semibold uppercase">
                                                {{ $user->employee?->account_role ? strtoupper($user->employee->account_role) : 'UNASSIGNED' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-gray-400">Status</span>
                                            <span class="inline-flex px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-semibold uppercase">Pending</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center justify-end gap-2">
                                        <button type="button"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm approve-btn"
                                                data-approve-action="{{ route('employee-accounts.approve', $user) }}"
                                                data-employee-name="{{ $user->employee?->lastname ?? '-' }}, {{ $user->employee?->firstname ?? '-' }}"
                                                data-current-role="{{ $user->employee?->account_role ?? '' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ __('Approve') }}
                                        </button>
                                        <form action="{{ route('employee-accounts.reject', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm" onclick="return confirm('Reject and delete this account? This action cannot be undone.')">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-400">{{ __('No pending accounts') }}</p>
                                        <p class="text-xs text-gray-300 mt-1">{{ __('All registered accounts have been verified.') }}</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100" x-show="accountsTab === 'approved'" x-cloak>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">{{ __('Approved Accounts') }}</h2>
                                <p class="text-xs text-gray-400">{{ __('Active employee accounts') }}</p>
                            </div>
                            <span class="ml-auto px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                {{ $approvedAccounts->count() }} {{ __('active') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @forelse($approvedAccounts as $user)
                                <div class="border border-gray-100 rounded-2xl p-5 bg-white shadow-sm hover:shadow-md transition account-card" data-link="{{ $user->employee ? route('employee-accounts.show', $user) : '' }}">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <x-profile-avatar :user="$user" size="md" variant="green" />
                                            <div>
                                                @if($user->employee)
                                                    <a href="{{ route('employee-accounts.show', $user) }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-700 hover:underline">
                                                        {{ $user->employee->lastname }}, {{ $user->employee->firstname }} {{ $user->employee->middlename ?? '' }}
                                                    </a>
                                                @else
                                                    <p class="text-sm font-semibold text-gray-900">-</p>
                                                @endif
                                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ __('Approved') }}
                                        </span>
                                    </div>

                                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-gray-600">
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-gray-400">Division</span>
                                            <span class="font-semibold">
                                                {{ $user->employee?->division ?? '-' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-gray-400">Position</span>
                                            <span class="inline-flex px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 font-semibold uppercase">
                                                {{ $user->employee?->position ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-gray-400">Account Role</span>
                                            <span class="inline-flex px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 font-semibold uppercase">
                                                {{ $user->employee?->account_role ? strtoupper($user->employee->account_role) : 'UNASSIGNED' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full px-6 py-10 text-center text-gray-400 text-sm">
                                    {{ __('No approved accounts yet.') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="approveModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/50" id="approveBackdrop"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-800">Approve Account</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" id="approveClose">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="mt-2 text-sm text-gray-600" id="approveEmployeeName"></p>
            <form method="POST" id="approveForm" class="mt-5 space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Assign Account Role</label>
                    <select name="account_role" id="approveRole" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm bg-white">
                        <option value="">-- Select Role --</option>
                        <option value="employee">Employee</option>
                        <option value="hrstaff">HR Staff</option>
                        <option value="chief">Chief</option>
                        <option value="regionaldirector">Regional Director</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button"
                            class="px-4 py-2.5 text-sm font-semibold text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                            id="approveCancel">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2.5 text-sm font-bold text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Approve
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const approveButtons = document.querySelectorAll('.approve-btn');
        const approveModal = document.getElementById('approveModal');
        const approveBackdrop = document.getElementById('approveBackdrop');
        const approveClose = document.getElementById('approveClose');
        const approveCancel = document.getElementById('approveCancel');
        const approveForm = document.getElementById('approveForm');
        const approveRole = document.getElementById('approveRole');
        const approveEmployeeName = document.getElementById('approveEmployeeName');

        function openApproveModal(event) {
            const button = event.currentTarget;
            const action = button.getAttribute('data-approve-action');
            const employeeName = button.getAttribute('data-employee-name');
            const currentRole = button.getAttribute('data-current-role');

            approveForm.setAttribute('action', action);
            approveEmployeeName.textContent = 'Assign account role for: ' + employeeName;
            approveRole.value = currentRole || '';

            approveModal.classList.remove('hidden');
            approveModal.classList.add('flex');
        }

        function closeApproveModal() {
            approveModal.classList.add('hidden');
            approveModal.classList.remove('flex');
        }

        approveButtons.forEach((button) => {
            button.addEventListener('click', openApproveModal);
        });
        approveBackdrop.addEventListener('click', closeApproveModal);
        approveClose.addEventListener('click', closeApproveModal);
        approveCancel.addEventListener('click', closeApproveModal);

        const accountCards = document.querySelectorAll('.account-card');
        accountCards.forEach((card) => {
            card.addEventListener('click', (event) => {
                if (event.target.closest('button, a, form')) {
                    return;
                }

                const link = card.getAttribute('data-link');
                if (link) {
                    window.location.href = link;
                }
            });
        });
    </script>
</x-app-layout>
