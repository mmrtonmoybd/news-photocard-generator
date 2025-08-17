<x-filament-panels::page>
    <div class="flex flex-col items-center space-y-6 px-4 sm:px-6 lg:px-8">
        {{-- Responsive Image --}}
        <img 
            src="{{ asset($record->generated_image) }}" 
            alt="Generated Image" 
            class="w-full sm:w-11/12 md:w-3/4 lg:w-1/2 xl:w-5/12 max-w-[900px] h-auto rounded-lg shadow-lg border"
        >

        {{-- Download Button --}}
        <a 
            href="{{ asset($record->generated_image) }}" 
            download
            class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow transition duration-200"
        >
            Download Image
        </a>
    </div>
</x-filament-panels::page>
