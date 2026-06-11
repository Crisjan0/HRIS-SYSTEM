<div x-show="printPreviewOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="printPreviewOpen = false"></div>
    <div class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 flex items-start justify-between gap-4">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#0038a8]">{{ __('UI Preview Only') }}</p>
                <h2 class="text-xl font-black text-gray-900">{{ __('Printable SALN Copy') }}</h2>
                <p class="text-xs text-gray-500 mt-1">{{ __('Sample printable layout preview') }}</p>
            </div>
            <button type="button" @click="printPreviewOpen = false" class="h-9 w-9 rounded-lg text-gray-400 hover:bg-gray-50 hover:text-gray-700">
                <svg class="mx-auto w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-6">
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Declarant') }}</p>
                        <p class="font-black text-gray-900">{{ auth()->user()->employee?->firstname }} {{ auth()->user()->employee?->lastname }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Filing Type') }}</p>
                        <p class="font-black text-gray-900">{{ __('Annual Filing') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('As Of') }}</p>
                        <p class="font-black text-gray-900">{{ __('December 31, 2026') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-5 py-3">{{ __('Section') }}</th>
                            <th class="px-5 py-3">{{ __('Sample Value') }}</th>
                            <th class="px-5 py-3">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr><td class="px-5 py-4 font-bold">Real Properties</td><td class="px-5 py-4 font-bold">2 declared</td><td class="px-5 py-4 font-bold text-emerald-600">Ready</td></tr>
                        <tr><td class="px-5 py-4 font-bold">Personal Properties</td><td class="px-5 py-4 font-bold">5 declared</td><td class="px-5 py-4 font-bold text-emerald-600">Ready</td></tr>
                        <tr><td class="px-5 py-4 font-bold">Liabilities</td><td class="px-5 py-4 font-bold">1 declared</td><td class="px-5 py-4 font-bold text-emerald-600">Ready</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" disabled class="rounded-lg bg-gray-100 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-not-allowed">{{ __('Download') }}</button>
                <button type="button" disabled class="rounded-lg bg-gray-100 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-not-allowed">{{ __('Print') }}</button>
            </div>
        </div>
    </div>
</div>
