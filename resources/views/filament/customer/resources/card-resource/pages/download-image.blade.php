<x-filament-panels::page>
    <x-filament::section>
        <div class="flex flex-col items-center space-y-6 px-4 sm:px-6 lg:px-8">
            {{-- Responsive Image --}}
            <img 
                src="{{ asset($record->generated_image) }}" 
                alt="Generated Image" 
                class="w-full sm:w-11/12 md:w-3/4 lg:w-1/2 xl:w-5/12 max-w-[900px] h-auto rounded-lg shadow-lg border"
            >

            {{-- Download Button --}}
            <x-filament::button
                tag="a"
                href="{{ asset($record->generated_image) }}"
                download
                color="primary"
            >
                Download Image
            </x-filament::button>

            {{-- Social Share Buttons --}}
            <div class="flex flex-wrap justify-center gap-3 mt-4">
                @php
                    $shareUrl = urlencode(route('social.share', [$record->hash]));
                    $shareTitle = urlencode('Check out this image!');
                @endphp

                <x-filament::button tag="a" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" color="info">
                    Facebook
                </x-filament::button>

                <x-filament::button tag="a" href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" color="success">
                    Twitter
                </x-filament::button>

                <x-filament::button tag="a" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" color="warning">
                    LinkedIn
                </x-filament::button>

                <x-filament::button tag="a" href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" color="success">
                    WhatsApp
                </x-filament::button>

                <x-filament::button tag="a" href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" color="danger">
                    Telegram
                </x-filament::button>

                <x-filament::button tag="a" href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $shareTitle }}" target="_blank" color="danger">
                    Pinterest
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
