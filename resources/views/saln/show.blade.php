<x-app-layout>
    @include('saln.partials.official-styles')

    <div class="py-12 print:py-0">
        <div class="max-w-[900px] mx-auto sm:px-6 lg:px-8 print:max-w-none print:mx-0 print:px-0">
            <div class="saln-actions mb-4 flex items-center justify-between gap-3 font-sans print:hidden">
                <a href="{{ route('salns.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Back
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('salns.download', ['saln' => $saln, 'inline' => 1]) }}" target="_blank" rel="noopener" data-no-transition class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#0038a8] px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-blue-100 hover:bg-[#002f8f] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v-6H6v6z"></path>
                        </svg>
                        Print SALN Copy
                    </a>
                    <a href="{{ route('salns.download', $saln) }}" data-no-transition class="rounded-lg bg-gray-800 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white hover:bg-gray-700">Save PDF Copy</a>
                </div>
            </div>
            <div class="bg-white shadow-2xl sm:rounded-lg border border-gray-200 p-6 md:p-10 print:shadow-none print:border-none print:p-4">

                <div class="saln-actions hidden">
                    <a href="{{ route('salns.index') }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900">Back</a>
                    <a href="{{ route('salns.download', ['saln' => $saln, 'inline' => 1]) }}" target="_blank" rel="noopener" data-no-transition class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#0038a8] px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-blue-100 hover:bg-[#002f8f] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v-6H6v6z"></path>
                        </svg>
                        Print SALN Copy
                    </a>
                    <a href="{{ route('salns.download', $saln) }}" data-no-transition class="px-6 py-2 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700 text-sm">Save PDF Copy</a>
                </div>

                <div class="saln-official saln-preview">
                    @include('saln.partials.content')
                </div>

            </div>
        </div>
    </div>
    <script>
        function updateSalnPageFooters() {
            document.querySelectorAll('.saln-official').forEach((salnCopy) => {
                const pages = Array.from(salnCopy.querySelectorAll('.saln-page'));
                const total = pages.length;

                pages.forEach((page, index) => {
                    const footer = page.querySelector('.saln-page-footer');
                    if (footer) {
                        footer.textContent = `Page ${index + 1} of ${total}`;
                    }
                });
            });
        }

        window.addEventListener('load', updateSalnPageFooters);
        window.addEventListener('beforeprint', updateSalnPageFooters);
    </script>
    @if(request()->boolean('print'))
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif
</x-app-layout>
