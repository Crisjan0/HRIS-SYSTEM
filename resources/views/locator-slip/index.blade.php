<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">My Locator Slips</h2>
                <a href="{{ route('locator-slips.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Create Locator Slip
                </a>
            </div>
            <div class="space-y-4">
                @forelse($locatorSlips as $slip)
                    <a href="{{ route('locator-slips.show', $slip) }}" class="block p-4 bg-gray-50 rounded-lg shadow-sm hover:bg-gray-100 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $slip->purpose }}</p>
                                <p class="text-sm text-gray-500">{{ $slip->date_covered }} | {{ \Carbon\Carbon::parse($slip->time_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slip->time_to)->format('h:i A') }}</p>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($slip->status == 'approved') bg-green-100 text-green-800 @endif
                                @if($slip->status == 'rejected') bg-red-100 text-red-800 @endif
                                @if($slip->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                @if($slip->status == 'approved by chief') bg-blue-100 text-blue-800 @endif
                            ">
                                {{ ucfirst($slip->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-12">
                        <p class="text-gray-500">No locator slips found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

