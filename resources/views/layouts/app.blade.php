<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Library')</title>

    <!-- Google Fonts: Montserrat (Headers) & Open Sans (Body) -->
    <link rel="icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Integrasi Vite untuk CSS dan JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Menerapkan Design System */
        :root {
            --deep-navy: #1B262C;
            --royal-ink: #0F4C75;
            --electric-blue: #3282B8;
            --frost-white: #BBE1FA;
            --pure-base: #FFFFFF;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--pure-base); /* Sesuai Design System */
            color: var(--royal-ink);
            min-height: 100vh;
            display: flex;
            margin: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            color: var(--deep-navy);
        }

        main {
            flex-grow: 1;
            padding: 2rem; /* Ruang agar tidak terlalu mepet */
        }
    </style>
</head>

<body>

    <!-- Sidebar menggunakan warna Deep Navy sesuai panduan -->
    <div style="background-color: #1B262C; width: 250px; min-height: 100vh;">
        @include('components.sidebar')
    </div>

    <main>
        @yield('content')
    </main>

</body>
</html>
