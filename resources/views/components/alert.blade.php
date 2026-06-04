@props(['type' => 'info', 'message' => null])

@php
    // Memadukan warna dari Design Palette kamu
    // Deep Navy: #1B262C | Royal Ink: #0F4C75 | Electric Blue: #3282B8 | Frost White: #BBE1FA

    $typeClasses = [
        // Info menggunakan warna brand kamu
        'info'    => 'bg-[#BBE1FA]/30 border-[#3282B8] text-[#0F4C75]',

        // Success & Error menggunakan warna semantik (hijau/merah) tapi dengan vibe yang cocok
        'success' => 'bg-green-50 border-green-500 text-green-800',
        'error'   => 'bg-red-50 border-red-500 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-800',
    ];

    $classes = $typeClasses[$type] ?? $typeClasses['info'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-center p-4 mb-6 text-sm border-l-4 rounded-r-lg shadow-sm $classes"]) }} role="alert">
    <svg class="flex-shrink-0 inline w-5 h-5 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
    </svg>
    <span class="sr-only">Notice</span>

    <div class="font-semibold tracking-wide">
        @if($message)
            {{ $message }}
        @else
            {{ $slot }}
        @endif
    </div>
</div>
