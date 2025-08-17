<x-filament::page>
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($packages as $package)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl border border-gray-200 overflow-hidden transition transform hover:-translate-y-1 h-full flex flex-col">
                    
                    <div class="p-5 flex flex-col flex-grow text-center">

                        {{-- Name + Details in same line --}}
                        <div class="flex items-center justify-center flex-wrap gap-2 mb-3">
                            <span class="font-bold text-lg text-gray-800">{{ $package->name }}</span>
                            <span class="text-gray-500 text-sm">{{ $package->details ?? '' }}</span>
                        </div>

                        {{-- Package Type --}}
                        <div class="text-sm font-medium text-gray-600">
                            {{ $package->is_credit ? 'Credit Package' : 'Day Package' }}
                        </div>

                        {{-- Days or Credit Value --}}
                        <!--
                        <div class="text-xl font-bold text-indigo-600 mt-1">
                            {{ $package->is_credit ? $package->credit . ' Credit' : $package->days . ' Days' }}
                        </div>
-->
                        <div class="text-xl font-bold text-indigo-600 mt-1">
                            {{ $package->days . ' Days' }}
                        </div>

                        {{-- Amount --}}
                        <div class="mt-3 text-center">
                            <div class="text-xs text-gray-500">Amount</div>
                            <div class="text-2xl font-extrabold text-green-600">
                                ${{ number_format($package->price, 2) }}
                            </div>
                        </div>

                        {{-- Always show Pay Button --}}
                        <div class="mt-5">
                           <x-filament::button wire:click="payNow({{ $package->id }})">
                                Pay Now
                            </x-filament::button>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
    Livewire.on('ssl-pay', url => {
        console.log('SSL PAY URL:', url);
        window.location.href = url;
    });
</script>

</x-filament::page>
