@props([
    'payload' => null,
    'event' => 'approval-selected',
    'empty' => 'No approval to track yet.',
])

<div class="mb-6" x-data="{ selectedApproval: @js($payload) }" x-on:{{ $event }}.window="selectedApproval = $event.detail">
    <div class="mx-auto max-w-5xl rounded-2xl border border-gray-100 bg-white px-6 py-5 text-center shadow-sm sm:px-8 sm:py-6">
        <template x-if="selectedApproval">
            <div>
                <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6">
                    <template x-for="(stage, index) in selectedApproval.stages" :key="stage.label">
                        <div class="inline-flex items-center gap-3 sm:gap-5">
                            <div class="inline-flex items-center gap-3" :title="stage.label + ': ' + stage.status">
                                <span class="h-4 w-4 rounded-full" :class="stage.status === 'approved' ? 'bg-green-500' : (stage.status === 'rejected' ? 'bg-red-500' : 'bg-gray-300')"></span>
                                <span class="text-sm font-bold text-gray-600 sm:text-base" x-text="stage.label"></span>
                            </div>
                            <span class="hidden text-xl font-bold text-gray-300 sm:inline" x-show="index < selectedApproval.stages.length - 1">&rarr;</span>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        <template x-if="! selectedApproval">
            <div class="text-base font-bold text-gray-500">{{ __($empty) }}</div>
        </template>
    </div>
</div>
