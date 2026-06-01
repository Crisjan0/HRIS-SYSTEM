<x-app-layout>
    <x-slot name="title">{{ __('Manage Compensatory Time-Off') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h1 class="text-2xl font-black text-gray-900">{{ __('Manage Compensatory Time-Off') }}</h1>
                </div>

                <div class="p-6" x-data="{ tab: '{{ $tab }}' }">
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button @click="tab = 'pending'"
                                    :class="tab === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                                {{ __('Pending CTO Requests') }}
                            </button>
                            <button @click="tab = 'all'"
                                    :class="tab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                                {{ __('All CTO Requests') }}
                            </button>
                        </nav>
                    </div>

                    <div x-show="tab === 'pending'" x-cloak class="space-y-4">
                        @forelse($pendingCtoRequests as $request)
                            @include('my-cto._request-card', ['request' => $request])
                        @empty
                            <div class="bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                                <p class="text-gray-500 italic font-medium">{{ __('No pending CTO requests.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <div x-show="tab === 'all'" x-cloak class="space-y-4">
                        @forelse($allCtoRequests as $request)
                            @include('my-cto._request-card', ['request' => $request])
                        @empty
                            <div class="bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                                <p class="text-gray-500 italic font-medium">{{ __('No approved or rejected CTO requests yet.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
