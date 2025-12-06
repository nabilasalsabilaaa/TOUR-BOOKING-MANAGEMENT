<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta dasar Laravel + responsif -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF token untuk form & AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title dinamis, default: TourBooking System -->
    <title>@yield('title', 'TourBooking System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN (utility class) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    // Konfigurasi Tailwind di sisi client (hanya untuk warna & font custom)
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    /* BLUE THEME */
                    primary: '#2c396d',         // Main Blue
                    'primary-dark': '#010d4c',  // Deep Navy
                    'primary-light': '#4556a6', // Soft Blue

                    /* GOLD ACCENTS */
                    accent: '#ffb524', 
                    'accent-light': '#f8ca5b',

                    /* FEEDBACK */
                    error: '#f50616',
                    success: '#10b981',
                    warning: '#f59e0b',
                    info: '#3b82f6',

                    /* EXTRA (dipakai untuk background global) */
                    background: '#eef2ff',
                },
                fontFamily: {
                    'figtree': ['Figtree', 'sans-serif'],
                }
            }
        }
    }
</script>


    <!-- Custom Styles (global untuk layout) -->
    <style>
    :root {
        /* === PRIMARY COLORS (BLUE THEME) === */
        --primary-dark: #010d4c;
        --primary: #2c396d;
        --primary-light: #4556a6;

        /* === ACCENT COLORS (GOLD THEME) === */
        --accent: #ffb524;
        --accent-light: #f8ca5b;

        /* === BACKGROUND & SURFACE === */
        --background: #eef2ff;
        --card: #ffffff;

        /* === TEXT COLORS === */
        --text: #1e1e2e;
        --text-light: #718096;

        /* === FEEDBACK COLORS === */
        --success: #10b981;
        --warning: #f59e0b;
        --error: #f50616;

        --border-light: #dce3ff;
    }

    /* Smooth scrolling untuk anchor (UX lebih halus) */
    html {
        scroll-behavior: smooth;
    }

    /* Animasi sederhana untuk hero atau elemen masuk dari bawah */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out; }

    /* Card reusable di seluruh app */
    .card-custom {
        background: var(--card);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(1, 13, 76, 0.15);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        box-shadow: 0 20px 25px -5px rgba(1, 13, 76, 0.2),
                    0 10px 10px -5px rgba(1, 13, 76, 0.1);
    }

    /* Gradient text — pakai warna primary */
    .gradient-text {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Body base style (warna background & font) */
    body {
        background-color: var(--background);
        font-family: 'Figtree', sans-serif;
    }

    /* Navbar gradient, class ini bisa dipakai di layouts.navigation */
    .navbar-custom {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
    }

    /* Tombol utama custom (di luar utility Tailwind) */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
        color: white;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(1, 13, 76, 0.3);
    }

    .btn-primary-custom:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(1, 13, 76, 0.4);
    }

    /* Status badge reusable (pending/confirmed/cancelled/completed) */
    .status-pending { background-color: #fef3c7; color: #d97706; }
    .status-confirmed { background-color: #d1fae5; color: #059669; }
    .status-cancelled { background-color: #fee2e2; color: #dc2626; }
    .status-completed { background-color: #dbeafe; color: #2563eb; }

    /* Utility tambahan */
    .text-primary { color: var(--primary-dark); }
    .bg-primary-light { background-color: var(--primary-light); }

    /* Scrollbar custom (hanya terlihat di browser yang support) */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }

    /* Fokus input lebih jelas dengan ring warna primary */
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(1, 13, 76, 0.2);
        border-color: var(--primary);
    }

    /* Hover row tabel global */
    .table-row-hover:hover {
        background-color: #eef2ff;
        transform: translateY(-1px);
    }
</style>


    {{-- Styles tambahan yang dipush dari view lain (misalnya halaman tertentu butuh CSS khusus) --}}
    @stack('styles')

    <!-- Scripts (Vite) - bundling css/js bawaan Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-figtree antialiased bg-gray-50">
    <!-- Wrapper utama page -->
    <div class="min-h-screen flex flex-col">
        <!-- Navigation global -->
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="flex-1">
            <!-- Flash Messages (saat ini dikomentar, bisa diaktifkan kalau mau pakai) -->
            {{-- <div class="container mx-auto px-4 pt-6">
                @if (session('success'))
                    <div class="card-custom p-4 mb-6 border-l-4 border-green-400 bg-green-50 flex items-center justify-between flash-alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-lg mr-3"></i>
                            <div>
                                <p class="text-green-800 font-medium">Success!</p>
                                <p class="text-green-700 text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-green-500 hover:text-green-700 alert-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="card-custom p-4 mb-6 border-l-4 border-red-400 bg-red-50 flex items-center justify-between flash-alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-lg mr-3"></i>
                            <div>
                                <p class="text-red-800 font-medium">Error!</p>
                                <p class="text-red-700 text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 alert-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="card-custom p-4 mb-6 border-l-4 border-yellow-400 bg-yellow-50 flex items-center justify-between flash-alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-lg mr-3"></i>
                            <div>
                                <p class="text-yellow-800 font-medium">Warning!</p>
                                <p class="text-yellow-700 text-sm">{{ session('warning') }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-yellow-500 hover:text-yellow-700 alert-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('status'))
                    <div class="card-custom p-4 mb-6 border-l-4 border-blue-400 bg-blue-50 flex items-center justify-between flash-alert">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 text-lg mr-3"></i>
                            <div>
                                <p class="text-blue-800 font-medium">Info</p>
                                <p class="text-blue-700 text-sm">{{ session('status') }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-blue-500 hover:text-blue-700 alert-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="card-custom p-4 mb-6 border-l-4 border-red-400 bg-red-50">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle text-red-500 text-lg mr-3"></i>
                            <p class="text-red-800 font-medium">Please fix the following errors:</p>
                        </div>
                        <ul class="text-red-700 text-sm list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div> --}}

            <!-- Main Content dari setiap halaman -->
            @yield('content')
        </main>

       <!-- Footer global -->
       <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 py-10">

                <!-- Top Section: brand + social -->
                <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-8">

                    <!-- Logo + Title -->
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start mb-2">
                            <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-map-marked-alt text-white text-sm"></i>
                            </div>
                            <span class="text-xl font-bold text-gray-800">TourBooking</span>
                        </div>
                        <p class="text-gray-600 text-sm max-w-sm">
                            Your Gateway to Unforgettable Adventures
                        </p>
                    </div>

                    <!-- Social Icons -->
                    <div class="flex items-center space-x-5">
                        <a href="#" class="text-gray-500 hover:text-primary transition">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary transition">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary transition">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary transition">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Divider + bottom links -->
                <div class="border-t border-gray-200 mt-8 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                    <p class="text-gray-500 text-sm">
                        &copy; 2024 TourBooking System. All rights reserved.
                    </p>

                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-500 hover:text-primary text-sm transition">Privacy Policy</a>
                        <a href="#" class="text-gray-500 hover:text-primary text-sm transition">Terms of Service</a>
                        <a href="#" class="text-gray-500 hover:text-primary text-sm transition">Contact Us</a>
                    </div>
                </div>

            </div>
        </footer>

    </div>

    <!-- Alpine.js untuk interaktivitas ringan (dropdown, toggle, dsb) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js" defer></script>

    <!-- Custom JavaScript global -->
    <script>
        // Toggle mobile menu (dipakai di layouts.navigation)
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (!menu) return;
            menu.classList.toggle('hidden');
        }

        // Close mobile menu ketika klik di luar area menu
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const menuButton = document.getElementById('mobile-menu-button');

            if (!menu || !menuButton) return;

            if (!menu.contains(event.target) && !menuButton.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Close flash alerts (manual close via tombol X)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.alert-close')) {
                const alert = e.target.closest('.flash-alert');
                if (alert) alert.remove();
            }
        });

        // Auto-dismiss flash messages setelah 5 detik (kalau ada)
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.flash-alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.parentElement) {
                        alert.remove();
                    }
                }, 5000);
            });
        });

        // Cegah multiple submit pada form (double klik tombol submit)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const submitButton = form.querySelector('button[type="submit"]');

            // Hanya jalankan kalau belum ditandai prevent-multiple-submit
            if (submitButton && !form.classList.contains('prevent-multiple-submit')) {
                form.classList.add('prevent-multiple-submit');
                submitButton.disabled = true;

                const originalText = submitButton.innerHTML;
                // Spinner sederhana (class spinner bisa didefinisikan di CSS global/app.css)
                submitButton.innerHTML = '<div class="spinner mr-2"></div> Processing...';

                // Re-enable button setelah 5 detik kalau terjadi error / tidak redirect
                setTimeout(() => {
                    if (submitButton.disabled) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        form.classList.remove('prevent-multiple-submit');
                    }
                }, 5000);
            }
        });

        // Helper: preview gambar (dipakai di form upload seperti Tour thumbnail)
        function setupImagePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            if (input && preview) {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        // Helper: format harga IDR dalam JavaScript (kalau mau dipakai di front-end)
        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
        }

        // Placeholder untuk inisialisasi tooltip (bisa diisi library tooltip nanti)
        function initTooltips() {
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach(element => {
                element.addEventListener('mouseenter', function(e) {
                    // Custom tooltip logic (optional, saat ini kosong)
                });
            });
        }

        // Inisialisasi awal ketika document siap
        document.addEventListener('DOMContentLoaded', function() {
            initTooltips();
        });
    </script>

    {{-- Script tambahan dari view lain --}}
    @stack('scripts')
</body>
</html>
