<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Locator Slip</h2>

            <form action="{{ route('locator-slips.update', $locatorSlip) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_covered" class="block font-medium text-sm text-gray-700">Date Covered</label>
                        <input type="date" name="date_covered" id="date_covered" value="{{ old('date_covered', $locatorSlip->date_covered) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="purpose" class="block font-medium text-sm text-gray-700">Purpose</label>
                        <input type="text" name="purpose" id="purpose" value="{{ old('purpose', $locatorSlip->purpose) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="time_from" class="block font-medium text-sm text-gray-700">Time From</label>
                        <input type="time" name="time_from" id="time_from" value="{{ old('time_from', $locatorSlip->time_from) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="time_to" class="block font-medium text-sm text-gray-700">Time To</label>
                        <input type="time" name="time_to" id="time_to" value="{{ old('time_to', $locatorSlip->time_to) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Update Locator Slip
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
