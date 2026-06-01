<x-app-layout>
    @include('saln.partials.official-styles')

    <div class="py-12 print:py-0">
        <div class="max-w-[900px] mx-auto sm:px-6 lg:px-8 print:max-w-none print:mx-0 print:px-0">
            <div class="bg-white shadow-2xl sm:rounded-lg border border-gray-200 p-6 md:p-10 print:shadow-none print:border-none print:p-4 saln-official">

                <div class="flex justify-end mb-4 gap-3">
                    <a href="{{ route('salns.index') }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900">Back</a>
                    <a href="{{ route('salns.download', $saln) }}" data-no-transition class="px-6 py-2 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700 text-sm">Download SALN</a>
                </div>

                @include('saln.partials.content')

            </div>
        </div>
    </div>
</x-app-layout>
