<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TourBooking System</title>

    {{-- Tailwind CDN + Font Awesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Konfigurasi warna Tailwind khusus untuk project TourBooking --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2c396d',
                        'primary-dark': '#010d4c',
                        'primary-light': '#4556a6',

                        secondary: '#ffb524',
                        accent: '#ffb524',
                        'accent-light': '#f8ca5b',

                        error: '#f50616',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-white">

@php
    // Path gambar ilustrasi login (disimpan di public/images/login-illustration.png)
    $loginIllustration = asset('images/login-illustration.png');
@endphp

{{-- ============================
     LAYOUT UTAMA
     - Fullscreen background image
     - Overlay gelap tipis
     - Panel login mengambang di sisi kanan
============================= --}}
<div class="relative min-h-screen w-full">

    {{-- BACKGROUND IMAGE full layar --}}
    <img src="{{ $loginIllustration }}"
         class="absolute inset-0 w-full h-full object-cover"
         alt="Login Illustration">

    {{-- OVERLAY GELAP TIPIS di atas gambar --}}
    <div class="absolute inset-0 bg-black/20"></div>

    {{-- WRAPPER PANEL LOGIN (floating di kanan) --}}
    <div class="absolute inset-y-0 right-0 flex items-center justify-center pr-10 pl-10">

        {{-- PANEL LOGIN --}}
        <div class="w-full max-w-sm bg-secondary rounded-3xl shadow-2xl px-8 py-8
                    translate-x-[-60px]"> {{-- geser panel sedikit ke kiri --}}
            
            {{-- ==================
                 HEADER FORM LOGIN
                 - Judul + subjudul
            =================== --}}
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-primary-dark">Login to continue</h1>
                <p class="text-sm text-primary-dark/80 mt-1">Masuk ke akun TourBooking</p>
            </div>

            {{-- ==================
                 ALERT STATUS SESSION
                 - Muncul jika ada session('status')
                 (contoh: "Password reset link sent", dll.)
            =================== --}}
            @if(session('status'))
                <div class="mb-4 p-3 bg-white/70 border border-white/40 rounded-xl
                            text-primary-dark text-sm flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            {{-- ==================
                 FORM LOGIN
                 - Email
                 - Password + toggle show/hide
                 - Remember me + Forgot password
            =================== --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- INPUT EMAIL --}}
                <div>
                    <label class="text-sm text-primary-dark font-medium">Email</label>
                    <div class="relative mt-1">
                        {{-- Icon envelope di sisi kiri input --}}
                        <i class="fas fa-envelope text-primary-dark/60 text-xs absolute left-3 top-3"></i>
                        
                        <input type="email" name="email" required
                               class="w-full pl-9 pr-3 py-2 rounded-full border border-white/80 bg-white
                                      text-sm placeholder:text-primary-dark/40
                                      focus:ring-2 focus:ring-primary-dark outline-none"
                               placeholder="Enter your email"
                               value="{{ old('email') }}">
                    </div>
                </div>

                {{-- INPUT PASSWORD --}}
                <div>
                    <label class="text-sm text-primary-dark font-medium">Password</label>
                    <div class="relative mt-1">
                        {{-- Icon lock di sisi kiri --}}
                        <i class="fas fa-lock text-primary-dark/60 text-xs absolute left-3 top-3"></i>

                        <input type="password" name="password" id="password"
                               class="w-full pl-9 pr-10 py-2 rounded-full border border-white/80 bg-white
                                      text-sm placeholder:text-primary-dark/40
                                      focus:ring-2 focus:ring-primary-dark outline-none"
                               placeholder="Enter your password">

                        {{-- Tombol kecil untuk show/hide password --}}
                        <button type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-2.5 text-primary-dark/60 text-xs">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- REMEMBER ME + FORGOT PASSWORD --}}
                <div class="flex items-center justify-between text-xs text-primary-dark/80">
                    {{-- Checkbox remember me --}}
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="h-3.5 w-3.5 rounded border-primary-dark">
                        Remember me
                    </label>

                    {{-- Link ke halaman lupa password (kalau route ada) --}}
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- TOMBOL LOGIN --}}
                <button type="submit"
                        class="w-full bg-primary-dark text-white py-2.5 rounded-full text-sm
                               font-medium shadow-md hover:bg-primary transition">
                    Login
                </button>

            </form>

            {{-- LINK KE REGISTER --}}
            <div class="mt-5 text-center text-xs text-primary-dark/80">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary-dark font-semibold hover:underline">
                    Sign up
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ==================
     SCRIPT: Toggle show/hide password
================== --}}
<script>
    function togglePassword() {
        const inp  = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');

        if (!inp || !icon) return;

        if (inp.type === 'password') {
            inp.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            inp.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
