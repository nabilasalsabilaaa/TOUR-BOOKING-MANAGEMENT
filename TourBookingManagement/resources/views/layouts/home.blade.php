@extends('layouts.app')

@section('title', 'Home - TourBooking System')

@section('content')

@php
    // Gambar hero – taruh di public/images/hero-tour2.png
    $heroImage = asset('images/hero-tour2.png');
@endphp

<!-- Hero Section -->
<section
    class="relative text-white py-20 lg:py-32 bg-cover bg-center"
    style="background-image: url('{{ $heroImage }}');"
>
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 items-center">
            <!-- LEFT (kosong biar teks geser ke kanan) -->
            <div class="hidden lg:block"></div>

            <!-- RIGHT Content -->
            <div class="text-left lg:text-right">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Discover Your Next
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400">
                        Adventure
                    </span>
                </h1>

                <p class="text-xl md:text-2xl mb-8 max-w-xl ml-auto opacity-90">
                    Jelajahi destinasi impianmu dengan paket tur terbaik dari 
                    <span class="font-semibold">TourBooking</span>.
                    Easy planning, safe trip, unforgettable memories.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="#tours"
                       class="bg-white text-primary-dark px-8 py-4 rounded-lg font-semibold text-lg
                              hover:bg-opacity-90 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i>
                        Explore Tours
                    </a>

                    @guest
                        <a href="{{ route('register') }}"
                           class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg
                                  hover:bg-white hover:text-primary transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-user-plus mr-2"></i>
                            Get Started
                        </a>
                    @else
                        <a href="{{ route('tours.index') }}"
                           class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg
                                  hover:bg-white hover:text-primary transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-map-marked-alt mr-2"></i>
                            Browse Tours
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Kenapa harus <span class="text-primary-dark">TourBooking?</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Kami bantu kamu planning perjalanan dengan cara yang simple, transparent, dan aman —
                so you can focus on enjoying the trip, not the hassle.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6 card-custom hover:transform hover:-translate-y-2 transition-all duration-300">
                <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Safe & Secure</h3>
                <p class="text-gray-600">
                    Keselamatan pelanggan adalah prioritas utama kami — mulai dari partner yang terverifikasi
                    hingga itinerary yang well-planned.
                </p>
            </div>
            
            <div class="text-center p-6 card-custom hover:transform hover:-translate-y-2 transition-all duration-300">
                <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tag text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Best Prices</h3>
                <p class="text-gray-600">
                    Harga bersahabat dengan value maksimal. No hidden fees, no drama —
                    just honest pricing for great experiences.
                </p>
            </div>
            
            <div class="text-center p-6 card-custom hover:transform hover:-translate-y-2 transition-all duration-300">
                <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">24/7 Support</h3>
                <p class="text-gray-600">
                    Tim support ready kapan saja kamu butuh bantuan —
                    sebelum, saat, dan setelah trip. Just chat, and we’ve got you.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Tours Section -->
<section id="tours" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Popular Tours</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Pilihan paket tur favorit yang paling sering dibooking travelers.  
                Perfect kalau kamu mau start from something tried & tested.
            </p>
        </div>

        @if(isset($popularTours) && $popularTours->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularTours as $tour)
                    @php
                        $imageUrl = $tour->thumbnail
                            ? asset('storage/' . $tour->thumbnail)
                            : 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=900&q=80';
                    @endphp
                    <div class="card-custom overflow-hidden hover:transform hover:-translate-y-2 transition-all duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $tour->name }}"
                                 class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Rp {{ number_format($tour->price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $tour->name }}</h3>
                            <div class="flex items-center text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                <span>{{ $tour->location }}</span>
                            </div>
                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="far fa-clock mr-2 text-primary"></i>
                                <span>{{ $tour->duration_days }} days</span>
                            </div>

                            @auth
                                <a href="{{ route('tours.index') }}"
                                   class="w-full bg-primary text-white py-3 px-4 rounded-lg font-medium hover:bg-primary-dark transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-calendar-plus mr-2"></i>View &amp; Book
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   class="w-full bg-primary text-white py-3 px-4 rounded-lg font-medium hover:bg-primary-dark transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Sign Up to Book
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @php
                $sampleTours = [
                    [
                        'name' => 'Bali Cultural Experience',
                        'location' => 'Bali, Indonesia',
                        'price' => 2500000,
                        'duration' => 5,
                        'image' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=900&q=80'
                    ],
                    [
                        'name' => 'Raja Ampat Diving Adventure',
                        'location' => 'West Papua, Indonesia',
                        'price' => 4500000,
                        'duration' => 7,
                        'image' => 'https://images.unsplash.com/photo-1587502536575-6dfba0a6e017?auto=format&fit=crop&w=900&q=80'
                    ],
                    [
                        'name' => 'Komodo Island Exploration',
                        'location' => 'East Nusa Tenggara',
                        'price' => 3200000,
                        'duration' => 4,
                        'image' => 'https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?auto=format&fit=crop&w=900&q=80'
                    ]
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($sampleTours as $tour)
                    <div class="card-custom overflow-hidden hover:transform hover:-translate-y-2 transition-all duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="{{ $tour['image'] }}"
                                 alt="{{ $tour['name'] }}"
                                 class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Rp {{ number_format($tour['price'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $tour['name'] }}</h3>
                            <div class="flex items-center text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                <span>{{ $tour['location'] }}</span>
                            </div>
                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="far fa-clock mr-2 text-primary"></i>
                                <span>{{ $tour['duration'] }} days</span>
                            </div>
                            @auth
                                <a href="{{ route('tours.index') }}"
                                   class="w-full bg-primary text-white py-3 px-4 rounded-lg font-medium hover:bg-primary-dark transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-calendar-plus mr-2"></i>Book Now
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   class="w-full bg-primary text-white py-3 px-4 rounded-lg font-medium hover:bg-primary-dark transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Sign Up to Book
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">About TourBooking</h2>
                <p class="text-lg text-gray-600 mb-6">
                    TourBooking adalah partner traveling-mu yang fokus bikin perjalanan terasa lebih mudah dan nyaman.  
                    We curate curated trips that highlight the best of Indonesia’s nature, culture, and local vibes.
                </p>
                <p class="text-lg text-gray-600 mb-8">
                    Misi kami: make travel accessible, safe, and memorable for everyone.  
                    Dengan tim berpengalaman dan local experts, setiap trip didesain supaya kamu bisa menikmati pengalaman yang autentik dan berkesan.
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        <span class="text-gray-700">Licensed Tour Operator</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        <span class="text-gray-700">Local Expert Guides</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        <span class="text-gray-700">Sustainable Tourism</span>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="card-custom p-6">
                    <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=900&q=80" 
                         alt="Travel Experience" 
                         class="w-full h-64 object-cover rounded-lg">
                </div>
                <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-primary-light rounded-2xl flex items-center justify-center">
                    <i class="fas fa-award text-primary text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                Contact <span class="text-primary-dark">Us</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Punya pertanyaan tentang paket tur, booking, atau kerja sama?  
                Kirim pesan ke kami, tim TourBooking akan segera merespons.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Info -->
            <div class="space-y-4 lg:pr-4">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Get in touch</h3>
                <p class="text-gray-600">
                    Isi form di samping atau hubungi kami melalui kontak berikut.
                </p>
                <div class="space-y-3 text-gray-700 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-primary mr-2"></i>
                        <span>+62 812-3456-7890</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-primary mr-2"></i>
                        <span>support@tourbooking.test</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                        <span>Makassar, Indonesia</span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="lg:col-span-2">
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="card-custom p-6 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-dark focus:border-primary-dark"
                                   placeholder="Your name">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-dark focus:border-primary-dark"
                                   placeholder="you@example.com">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-dark focus:border-primary-dark"
                               placeholder="Ask about package, booking, etc.">
                        @error('subject')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-dark focus:border-primary-dark"
                                  placeholder="Write your message here...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-primary text-white px-6 py-3 rounded-lg font-semibold text-sm md:text-base hover:bg-primary-dark transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-paper-plane text-xs"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gray-900 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Start Your Adventure?</h2>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
            Join thousands of satisfied travelers who have discovered the world with TourBooking.
            Sekarang giliran kamu.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}"
                   class="bg-primary text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-primary-dark transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Account
                </a>
            @else
                <a href="{{ route('tours.index') }}"
                   class="bg-primary text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-primary-dark transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-map-marked-alt mr-2"></i>
                    Browse Tours
                </a>
            @endguest

            <a href="#about"
               class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:-translate-y-1">
                <i class="fas fa-info-circle mr-2"></i>
                Learn More
            </a>
        </div>
    </div>
</section>

@endsection
