{{-- 
    Guest Layout Template
    Digunakan untuk halaman yang tidak memerlukan autentikasi (login, register, landing page, dll)
    Layout: resources/views/layouts/guest.blade.php
--}}

<!DOCTYPE html>
{{-- 
    Set bahasa HTML berdasarkan konfigurasi locale aplikasi.
    str_replace('_', '-', app()->getLocale()) mengubah format locale (misal: 'en_US' menjadi 'en-US')
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Meta tags dasar untuk encoding karakter dan responsive design --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        {{-- CSRF Token untuk keamanan form Laravel --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- 
            Dynamic page title dengan @yield.
            Default title: 'TourBooking System' jika section 'title' tidak diisi di child view
        --}}
        <title>@yield('title', 'TourBooking System')</title>

        {{-- Fonts --}}
        {{-- 
            Menggunakan font dari Bunny Fonts (alternatif Google Fonts) 
            Font family: Figtree dengan weight 400, 500, 600
        --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- 
            Vite asset bundling untuk CSS dan JavaScript.
            resources/css/app.css dan resources/js/app.js akan dikompilasi oleh Vite
        --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    {{-- 
        Body dengan styling dasar:
        - Font family Figtree (sesuai CDN di atas)
        - Warna teks gray-900 (hampir hitam)
        - Antialiased untuk smoothing font
    --}}
    <body class="font-sans text-gray-900 antialiased">
        {{-- 
            Section utama untuk konten halaman.
            Semua konten dari view child akan di-render di sini
        --}}
        @yield('content')
    </body>
</html>