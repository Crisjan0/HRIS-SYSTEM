<x-app-layout>
    @include('saln.partials.official-styles')

    <div class="py-12 print:py-0" x-data="{ printPreviewOpen: false }">
        <div class="max-w-[900px] mx-auto sm:px-6 lg:px-8 print:max-w-none print:mx-0 print:px-0">
            <div class="bg-white shadow-2xl sm:rounded-lg border border-gray-200 p-6 md:p-10 print:shadow-none print:border-none print:p-4">

                <div class="saln-actions flex justify-end mb-4 gap-3 font-sans print:hidden">
                    <a href="{{ route('salns.index') }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900">Back</a>
                    <button type="button" @click="printPreviewOpen = true" class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#0038a8] px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-blue-100 hover:bg-[#002f8f] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v-6H6v6z"></path>
                        </svg>
                        Print SALN Copy
                    </button>
                    <a href="{{ route('salns.download', $saln) }}" data-no-transition class="px-6 py-2 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700 text-sm">Download SALN</a>
                </div>

                <div class="saln-official">
                    @include('saln.partials.content')
                </div>

            </div>
        </div>

        @include('saln.partials.print-preview-modal')
    </div>
</x-app-layout>
