{{-- 
    Main Navigation Bar
    File: resources/views/components/navigation.blade.php
    Digunakan di: layouts/app.blade.php
    Fitur: Responsive navbar dengan conditional menu berdasarkan role user
--}}

<nav class="shadow-lg bg-[#010d4c]">
    {{-- Container utama dengan max-width dan padding responsif --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- ==================== KIRI: LOGO DAN MENU DESKTOP ==================== --}}
            <div class="flex items-center">
                {{-- Logo dengan link ke home --}}
                <a href="{{ route('home') }}" class="flex items-center">
                    {{-- Logo container dengan warna kuning (#ffb524) dan bentuk rounded --}}
                    <div class="w-9 h-9 bg-[#ffb524] rounded-lg flex items-center justify-center mr-2 shadow-md">
                        <i class="fas fa-map-marked-alt text-[#010d4c] text-sm"></i>
                    </div>
                    {{-- Nama brand --}}
                    <span class="text-xl font-extrabold tracking-wide text-white">TourBooking</span>
                </a>

                {{-- Menu desktop - hidden di mobile, tampil di sm ke atas --}}
                <div class="hidden sm:flex sm:ml-8 sm:space-x-1">
                    {{-- 
                        Conditional rendering berdasarkan status autentikasi dan role user:
                        1. Admin: Dashboard, Tours, Schedules, Bookings
                        2. Customer: Dashboard, My Bookings, Find Tours
                        3. Guest: Public links (Tours, About, Contact dengan anchor link)
                    --}}
                    @auth
                        @if(Auth::user()->role === 'admin')
                            {{-- Menu untuk admin --}}
                            <x-nav-link route="admin.dashboard" icon="tachometer-alt">Dashboard</x-nav-link>
                            <x-nav-link route="admin.tours.index" icon="map">Tours</x-nav-link>
                            <x-nav-link route="admin.schedules.index" icon="calendar-alt">Schedules</x-nav-link>
                            <x-nav-link route="admin.bookings.index" icon="bookmark">Bookings</x-nav-link>

                        @else
                            {{-- Menu untuk customer --}}
                            <x-nav-link route="customer.dashboard" icon="tachometer-alt">Dashboard</x-nav-link>
                            <x-nav-link route="customer.bookings.index" icon="history">My Bookings</x-nav-link>
                            <x-nav-link route="tours.index" icon="search">Find Tours</x-nav-link>
                        @endif

                    @else
                        {{-- Menu untuk guest/pengunjung --}}
                        <x-nav-link href="{{ route('home') }}#tours" icon="map-marked-alt">Tours</x-nav-link>
                        <x-nav-link href="{{ route('home') }}#about" icon="info-circle">About</x-nav-link>
                        <x-nav-link href="{{ route('home') }}#contact" icon="envelope">Contact</x-nav-link>
                    @endauth
                </div>
            </div>

            {{-- ==================== KANAN: USER SECTION & MOBILE BUTTON ==================== --}}
            <div class="flex items-center space-x-3">
                {{-- Conditional rendering untuk user authenticated vs guest --}}
                @auth
                    {{-- Nama user dengan badge --}}
                    <div class="hidden sm:flex items-center space-x-2 bg-[#2c396d] px-3 py-1 rounded-full shadow">
                        {{-- Avatar/icon user --}}
                        <div class="w-7 h-7 bg-[#ffb524] rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-[#010d4c] text-xs"></i>
                        </div>
                        {{-- Nama user --}}
                        <span class="text-white text-sm font-semibold">{{ Auth::user()->name }}</span>
                    </div>

                    {{-- Tombol profile --}}
                    <a href="{{ route('profile.edit') }}" class="nav-icon-btn" title="Profile">
                        <i class="fas fa-user-edit"></i>
                    </a>

                    {{-- Form logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-icon-btn" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>

                @else
                    {{-- Untuk guest: tombol login dan sign up --}}
                    <a href="{{ route('login') }}" class="btn-outline">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">Sign Up</a>
                @endauth

                {{-- ==================== TOMBOL MOBILE MENU ==================== --}}
                {{-- 
                    Tombol hamburger menu yang hanya tampil di mobile (sm:hidden)
                    Memanggil fungsi JavaScript toggleMobileMenu()
                --}}
                <button id="mobile-menu-button" onclick="toggleMobileMenu()"
                    class="sm:hidden text-white hover:bg-[#4556a6] p-2 rounded-lg transition">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== MOBILE MENU ==================== --}}
    {{-- 
        Menu mobile yang disembunyikan secara default (hidden)
        Akan muncul saat tombol hamburger diklik
    --}}
    <div id="mobile-menu" class="hidden sm:hidden bg-white shadow-lg">
        <div class="px-4 py-4 space-y-1">
            {{-- 
                Conditional menu mobile dengan struktur sama seperti desktop
                Tapi dengan komponen mobile-nav yang berbeda styling
            --}}
            @auth
                @if(Auth::user()->role === 'admin')
                    <x-mobile-nav route="admin.dashboard" icon="tachometer-alt">Dashboard</x-mobile-nav>
                    <x-mobile-nav route="admin.tours.index" icon="map">Tours</x-mobile-nav>
                    <x-mobile-nav route="admin.schedules.index" icon="calendar-alt">Schedules</x-mobile-nav>
                    <x-mobile-nav route="admin.bookings.index" icon="bookmark">Bookings</x-mobile-nav>

                @else
                    <x-mobile-nav route="customer.dashboard" icon="tachometer-alt">Dashboard</x-mobile-nav>
                    <x-mobile-nav route="customer.bookings.index" icon="history">My Bookings</x-mobile-nav>
                    <x-mobile-nav route="tours.index" icon="search">Find Tours</x-mobile-nav>
                @endif

                {{-- Separator untuk menu user (profile & logout) --}}
                <div class="border-t border-gray-300 mt-3 pt-3">
                    <x-mobile-nav route="profile.edit" icon="user-edit">Profile</x-mobile-nav>

                    {{-- Form logout untuk mobile --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-[#4556a6] hover:text-white transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>Logout
                        </button>
                    </form>
                </div>

            @else
                {{-- Menu untuk guest di mobile --}}
                <x-mobile-nav href="{{ route('home') }}#tours" icon="map-marked-alt">Tours</x-mobile-nav>
                <x-mobile-nav href="{{ route('home') }}#about" icon="info-circle">About</x-mobile-nav>
                <x-mobile-nav href="{{ route('home') }}#contact" icon="envelope">Contact</x-mobile-nav>

                {{-- Separator untuk auth links --}}
                <div class="border-t border-gray-300 mt-3 pt-3">
                    <x-mobile-nav route="login" icon="sign-in-alt">Login</x-mobile-nav>
                    <x-mobile-nav route="register" icon="user-plus">Sign Up</x-mobile-nav>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- ==================== STYLE COMPONENTS (INLINE) ==================== --}}
{{-- 
    Style inline untuk komponen UI navbar
    @once directive memastikan style ini hanya dimuat sekali
--}}
@once
<style>
    /* 
        Style untuk tombol icon (profile, logout, dll)
        Menggunakan warna dari palette design system
    */
    .nav-icon-btn {
        color: #ffffff;
        padding: 0.5rem;            /* p-2 */
        border-radius: 0.5rem;      /* rounded-lg */
        font-size: 0.875rem;        /* text-sm */
        transition: all 0.2s ease;  /* transition */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .nav-icon-btn:hover {
        background-color: #4556a6;  /* hover:bg-[#4556a6] */
    }

    /* 
        Primary button style (untuk Sign Up)
        Warna primer: #ffb524 (kuning), text: #010d4c (biru gelap)
    */
    .btn-primary {
        background-color: #ffb524;  /* bg-[#ffb524] */
        color: #010d4c;             /* text-[#010d4c] */
        padding: 0.5rem 1rem;       /* px-4 py-2 */
        border-radius: 0.5rem;      /* rounded-lg */
        font-size: 0.875rem;        /* text-sm */
        font-weight: 600;           /* font-semibold */
        box-shadow: 0 4px 6px rgba(0,0,0,0.15); /* shadow-md kira-kira */
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background-color: #f8ca5b;  /* hover:bg-[#f8ca5b] */
    }

    /* 
        Outline button style (untuk Login)
        Border putih dengan hover effect putih
    */
    .btn-outline {
        color: #ffffff;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid #ffffff;
        transition: all 0.2s ease;
    }
    .btn-outline:hover {
        background-color: #ffffff;
        color: #010d4c;
    }
</style>
@endonce

{{-- ==================== JAVASCRIPT ==================== --}}
{{-- 
    Fungsi JavaScript sederhana untuk toggle mobile menu
    Bisa dipindah ke file JS terpisah untuk production
--}}
<script>
    /**
     * Fungsi untuk menampilkan/sembunyikan mobile menu
     * Menggunakan toggle class 'hidden' pada elemen mobile-menu
     */
    function toggleMobileMenu() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    }
</script>