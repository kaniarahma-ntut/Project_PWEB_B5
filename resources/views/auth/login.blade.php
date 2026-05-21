<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Library</title>

    <!-- Import Google Fonts: Montserrat (Header) & Open Sans (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#1B262C',      // Deep Navy - Utama/Dark Accent
                        ink: '#0F4C75',       // Royal Ink - Secondary/Text Medium
                        electric: '#3282B8',  // Electric Blue - Accent/Action
                        frost: '#BBE1FA',     // Frost White - Light UI/Highlight
                        base: '#FFFFFF',      // Pure Base - Base Background
                    },
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'], // Header & Sub-Header
                        sans: ['Open Sans', 'sans-serif'],        // Body Text
                    }
                }
            }
        }
    </script>
    <style>
        /* CSS Tambahan untuk aksen industrial/solid */
        .industrial-pattern {
            background-image: radial-gradient(#3282B8 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.1;
        }
    </style>
</head>

<body class="bg-base font-sans text-ink min-h-screen flex antialiased">

    <!-- Sisi Kiri: Branding (Deep Navy) -->
    <!-- Vibe: Profesional, Maskulin, Kokoh -->
    <div class="hidden lg:flex lg:w-5/12 bg-navy relative flex-col justify-between p-12 overflow-hidden shadow-[4px_0_24px_rgba(0,0,0,0.15)] z-10">
        <!-- Aksen Background Pattern -->
        <div class="absolute inset-0 industrial-pattern"></div>

        <!-- Aksen Garis Biru Elektrik di atas -->
        <div class="absolute top-0 left-0 w-full h-2 bg-electric"></div>

        <div class="relative z-10 mt-10">
            <h1 class="font-montserrat font-bold text-4xl text-base tracking-wide mb-2">
                SMART <span class="text-electric">LIBRARY</span>
            </h1>
            <p class="font-montserrat font-semibold text-frost text-lg border-l-4 border-electric pl-3">
                Sistem Manajemen Perpustakaan Terpadu
            </p>
        </div>

        <!-- Ilustrasi/Aksen Geometris Industrial -->
        <div class="relative z-10 w-full max-w-sm">
            <div class="w-32 h-2 bg-electric mb-4"></div>
            <p class="text-frost text-sm leading-relaxed opacity-80 font-sans">
                Infrastruktur data perpustakaan yang andal, aman, dan efisien.
                Sistem ini dirancang untuk memastikan stabilitas akses informasi
                dengan menggunakan protokol otentikasi terpusat.
            </p>
        </div>

        <div class="relative z-10 text-xs text-frost opacity-60 font-sans">
            &copy; {{ date('Y') }} Smart Library System. All rights reserved.
        </div>
    </div>

    <div class="w-full lg:w-7/12 flex flex-col justify-center items-center p-8 sm:p-12 relative">

        <!-- Logo mobile view (sembunyi di desktop) -->
        <div class="lg:hidden mb-12 text-center">
            <h1 class="font-montserrat font-bold text-3xl text-navy tracking-wide mb-1">
                SMART <span class="text-electric">LIBRARY</span>
            </h1>
            <div class="w-16 h-1 bg-electric mx-auto mt-2"></div>
        </div>

        <div class="w-full max-w-md bg-base rounded-none">

            <div class="mb-10">
                <h2 class="font-montserrat font-bold text-3xl text-navy mb-3">
                    Akses Sistem
                </h2>
                <p class="font-sans font-regular text-ink text-base">
                    Silakan masuk menggunakan akun Google Anda untuk mengakses layanan dan dashboard perpustakaan.
                </p>
            </div>

            <!-- Menampilkan Error dari AuthController -->
            @if(session('error'))
                <div class="mb-6 p-4 border-l-4 border-red-500 bg-red-50 text-red-700 font-sans text-sm shadow-sm" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold block">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Menampilkan Success (Misal saat Logout) -->
            @if(session('success'))
                <div class="mb-6 p-4 border-l-4 border-electric bg-frost bg-opacity-30 text-navy font-sans text-sm shadow-sm" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-electric" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold block">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="mt-8">
                <!-- Sesuai arahan AuthController, endpoint adalah /auth/google -->
                <a href="{{ url('/auth/google') }}"
                   class="group w-full flex items-center justify-center py-3.5 px-4 bg-electric hover:bg-navy text-base transition-colors duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-electric rounded-sm relative overflow-hidden">

                    <!-- Animasi hover subtle -->
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>

                    <!-- Icon Google -->
                    <span class="bg-base p-1.5 rounded-sm mr-3 z-10 flex items-center justify-center">
                        <svg class="w-5 h-5" viewBox="0 0 48 48">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        </svg>
                    </span>

                    <span class="font-sans font-bold text-[15px] z-10 tracking-wide">
                        Lanjutkan dengan Google
                    </span>
                </a>
            </div>

            <!-- Pesan Privasi / Keamanan -->
            <div class="mt-8 pt-6 border-t border-frost/50 flex items-start">
                <svg class="w-5 h-5 text-ink/60 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <p class="text-xs text-ink/70 font-sans leading-relaxed">
                    Sistem ini dilindungi oleh otentikasi Google yang dienkripsi.
                    Dengan masuk, Anda menyetujui kebijakan akses dan penggunaan data Smart Library.
                </p>
            </div>

        </div>
    </div>

</body>
</html>
