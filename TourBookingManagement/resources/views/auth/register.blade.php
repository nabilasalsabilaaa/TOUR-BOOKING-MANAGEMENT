<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TourBooking System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2c396d',
                        'primary-dark': '#010d4c',
                        'primary-light': '#4556a6',
                        secondary: '#ffb524',
                        error: '#f50616',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-white">

@php
    $registerIllustration = asset('images/regist4-illustration.png');
@endphp

{{-- WRAPPER PARENT --}}
<div class="min-h-screen w-full flex items-center justify-start relative">

    {{-- LEFT IMAGE (tinggi mengikuti konten) --}}
    <div class="absolute left-0 top-0 bottom-0 w-[100%] overflow-hidden">

        <img src="{{ $registerIllustration }}"
             class="w-full h-full object-cover object-center"
             alt="Register Background">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/20"></div>
    </div>

    {{-- FORM PANEL --}}
    <div class="relative z-20 w-full max-w-md ml-16 mt-10 mb-10">

        <div class="bg-primary-dark rounded-3xl shadow-2xl px-10 py-10 translate-x-[60px]">

            {{-- Header --}}
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-secondary">Create your account</h1>
                <p class="text-sm text-white/80 mt-1">
                    Daftar untuk mulai menggunakan TourBooking
                </p>
            </div>

            {{-- SESSION --}}
            @if(session('status'))
                <div class="mb-4 p-3 bg-white/70 border border-white/40 rounded-xl
                            text-white text-sm flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            {{-- FORM --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- NAME --}}
                <div>
                    <label class="text-sm text-white font-medium">Full Name</label>
                    <div class="relative mt-1">
                        <i class="fas fa-user text-secondary text-xs absolute left-3 top-3"></i>
                        <input type="text" name="name" required
                               class="w-full pl-9 pr-3 py-2 rounded-full bg-white border border-white/70
                                      text-sm placeholder:text-primary-dark/40 focus:ring-2
                                      focus:ring-secondary outline-none"
                               placeholder="Enter your full name">
                    </div>
                </div>

                {{-- PHONE --}}
                <div>
                    <label class="text-sm text-white font-medium">Phone Number</label>
                    <div class="relative mt-1">
                        <i class="fas fa-phone text-secondary text-xs absolute left-3 top-3"></i>
                        <input type="text" name="phone" required
                               class="w-full pl-9 pr-3 py-2 rounded-full bg-white border border-white/70
                                      text-sm placeholder:text-primary-dark/40 focus:ring-2
                                      focus:ring-secondary outline-none"
                               placeholder="Enter your phone number">
                    </div>
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="text-sm text-white font-medium">Email</label>
                    <div class="relative mt-1">
                        <i class="fas fa-envelope text-secondary text-xs absolute left-3 top-3"></i>
                        <input type="email" name="email" required
                               class="w-full pl-9 pr-3 py-2 rounded-full bg-white border border-white/70
                                      text-sm placeholder:text-primary-dark/40 focus:ring-2
                                      focus:ring-secondary outline-none"
                               placeholder="Enter your email">
                    </div>
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="text-sm text-white font-medium">Password</label>
                    <div class="relative mt-1">
                        <i class="fas fa-lock text-secondary text-xs absolute left-3 top-3"></i>
                        <input id="password" type="password" name="password" required
                               class="w-full pl-9 pr-10 py-2 rounded-full bg-white border border-white/70
                                      text-sm placeholder:text-primary-dark/40 focus:ring-2
                                      focus:ring-secondary outline-none"
                               placeholder="Create a password">

                        <button type="button"
                                onclick="togglePassword('password','iconPass')"
                                class="absolute right-3 top-2.5 text-primary-dark/60 text-xs">
                            <i id="iconPass" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- CONFIRM --}}
                <div>
                    <label class="text-sm text-white font-medium">Confirm Password</label>
                    <div class="relative mt-1">
                        <i class="fas fa-lock text-secondary text-xs absolute left-3 top-3"></i>
                        <input id="password_confirmation" type="password"
                               name="password_confirmation" required
                               class="w-full pl-9 pr-10 py-2 rounded-full bg-white border border-white/70
                                      text-sm placeholder:text-primary-dark/40 focus:ring-2
                                      focus:ring-secondary outline-none"
                               placeholder="Confirm your password">

                        <button type="button"
                                onclick="togglePassword('password_confirmation','iconPass2')"
                                class="absolute right-3 top-2.5 text-primary-dark/60 text-xs">
                            <i id="iconPass2" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- SUBMIT --}}
                <button type="submit"
                        class="w-full bg-secondary text-primary-dark py-2.5 rounded-full text-sm font-medium
                               shadow-md hover:bg-secondary/80 transition">
                    Create Account
                </button>
            </form>

            {{-- LOGIN LINK --}}
            <div class="mt-5 text-center text-xs text-white/80">
                Already have an account?
                <a href="{{ route('login') }}" class="text-secondary font-semibold hover:underline">
                    Sign in
                </a>
            </div>
        </div>
    </div>
</div>


<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
