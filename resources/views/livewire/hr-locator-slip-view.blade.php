<div class="p-4 sm:p-6 lg:p-8">
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Locator Slips</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Covered</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($locatorSlips as $slip)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $slip->employee->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $slip->date_covered }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $slip->purpose }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $slip->time_from }} - {{ $slip->time_to }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($slip->status == 'approved') bg-green-100 text-green-800 @endif
                                    @if($slip->status == 'rejected') bg-red-100 text-red-800 @endif
                                    @if($slip->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                ">
                                    {{ ucfirst($slip->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($slip->status == 'pending')
                                    <button wire:click="approve({{ $slip->id }})" class="text-indigo-600 hover:text-indigo-900">Approve</button>
                                    <button wire:click="reject({{ $slip->id }})" class="text-red-600 hover:text-red-900 ml-4">Reject</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No locator slips found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

