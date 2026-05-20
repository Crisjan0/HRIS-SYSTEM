<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Locator Slip Details</h2>
                <div class="flex space-x-2">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-600 focus:outline-none focus:border-gray-600 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Back
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800">Date Covered</h3>
                        <p class="text-sm text-gray-500">{{ $locatorSlip->date_covered }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Purpose</h3>
                        <p class="text-sm text-gray-500">{{ $locatorSlip->purpose }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Time</h3>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($locatorSlip->time_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($locatorSlip->time_to)->format('h:i A') }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800">Status</h3>
                                                    <p class="text-gray-700">
                                @if ($locatorSlip->status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @elseif ($locatorSlip->status == 'approved by chief')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Approved by Chief
                                    </span>
                                @elseif ($locatorSlip->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Pending
                                    </span>
                                @elseif ($locatorSlip->status == 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($locatorSlip->status) }}
                                    </span>
                                @endif
                            </p>
                    </div>
                    @if($locatorSlip->approved_by_chief_name)
                    <div>
                        <h3 class="font-semibold text-gray-800">Approved by Chief</h3>
                        <p class="text-sm text-gray-500">{{ $locatorSlip->approved_by_chief_name }} on {{ $locatorSlip->chief_approval_date }}</p>
                    </div>
                    @endif
                    @if($locatorSlip->approved_by_regional_director_name)
                    <div>
                        <h3 class="font-semibold text-gray-800">Approved by Regional Director</h3>
                        <p class="text-sm text-gray-500">{{ $locatorSlip->approved_by_regional_director_name }} on {{ $locatorSlip->regional_director_approval_date }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
