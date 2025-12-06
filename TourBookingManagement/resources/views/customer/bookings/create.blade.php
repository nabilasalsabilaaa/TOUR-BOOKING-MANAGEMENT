@extends('layouts.app')

@section('title', 'Book Tour - TourBooking')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- =========================
             HEADER HALAMAN BOOKING
             ========================= --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        {{-- Tombol back ke halaman sebelumnya --}}
                        <a href="{{ url()->previous() }}" class="mr-4 text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Book Tour</h1>
                            <p class="text-gray-600 mt-2">Complete your booking for {{ $schedule->tour->name }}</p>
                        </div>
                    </div>
                </div>
                {{-- Info kecil di kanan atas --}}
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <i class="fas fa-shield-alt text-primary"></i>
                    <span>Secure Booking • Instant Confirmation</span>
                </div>
            </div>
        </div>

        {{-- =========================
             GRID: FORM (2 kolom) + RINGKASAN TOUR (1 kolom)
             ========================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- =====================
                 KOLOM KIRI: BOOKING FORM
                 ===================== --}}
            <div class="lg:col-span-2">
                <div class="card-custom p-6">
                    {{-- Header form --}}
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-light mr-4">
                            <i class="fas fa-calendar-check text-primary text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Booking Details</h2>
                            <p class="text-gray-600 text-sm">Fill in your booking information</p>
                        </div>
                    </div>
                    
                    {{-- FORM BOOKING (POST ke bookings.store dengan schedule terkait) --}}
                    <form action="{{ route('customer.bookings.store', $schedule) }}" method="POST" class="prevent-multiple-submit">
                        @csrf

                        <div class="space-y-6">
                            {{-- ================
                                 RINGKASAN TOUR DI ATAS FORM
                                ================ --}}
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 text-lg">{{ $schedule->tour->name }}</h3>
                                        <div class="flex items-center mt-2 text-sm text-gray-600">
                                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                            <span>{{ $schedule->tour->location }}</span>
                                        </div>
                                        <div class="flex items-center mt-1 text-sm text-gray-600">
                                            <i class="far fa-calendar mr-2 text-primary"></i>
                                            <span>{{ $schedule->date->format('F j, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-primary">
                                            {{-- Harga per orang --}}
                                            Rp {{ number_format($schedule->tour->price, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-gray-500">per person</div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================
                                 JUMLAH TAMU / GUESTS
                                ================ --}}
                            <div>
                                <label for="guests" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-users mr-2 text-primary"></i>
                                    Number of Guests *
                                </label>
                                <div class="relative">
                                    <input type="number" name="guests" id="guests" min="1" max="{{ $schedule->available_slots }}" 
                                           value="{{ old('guests', 1) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                           required>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-sm text-gray-500">
                                        Maximum: {{ $schedule->available_slots }} slots available
                                    </p>
                                    {{-- Pesan error validasi untuk guests --}}
                                    @error('guests')
                                        <p class="text-sm text-error flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- ================
                                 CATATAN / SPECIAL REQUEST
                                ================ --}}
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-sticky-note mr-2 text-primary"></i>
                                    Special Requests (Optional)
                                </label>
                                <textarea name="notes" id="notes" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none resize-none"
                                          placeholder="Any special requirements, dietary restrictions, or requests...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-2 text-sm text-error flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ================
                                 RINCIAN HARGA / PRICE SUMMARY
                                ================ --}}
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Price Summary</h3>
                                <div class="space-y-2">
                                    {{-- Subtotal: guests x price per person (di-update via JS) --}}
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-2 text-primary"></i>
                                            <span id="guests-count">1</span> guest(s) × Rp {{ number_format($schedule->tour->price, 0, ',', '.') }}
                                        </span>
                                        <span id="subtotal">Rp {{ number_format($schedule->tour->price, 0, ',', '.') }}</span>
                                    </div>
                                    {{-- Service fee (saat ini 0) --}}
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <i class="fas fa-receipt mr-2 text-primary"></i>
                                            Service fee
                                        </span>
                                        <span>Rp 0</span>
                                    </div>
                                    {{-- Total keseluruhan --}}
                                    <div class="border-t pt-2 mt-2">
                                        <div class="flex justify-between items-center font-semibold text-lg text-gray-900">
                                            <span>Total Amount</span>
                                            <span id="totalAmount" class="text-primary text-xl">
                                                Rp {{ number_format($schedule->tour->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================
                                 INFORMASI / TERMS
                                ================ --}}
                            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                                    <div class="text-sm text-yellow-700">
                                        <p class="font-medium">Important Information</p>
                                        <p class="mt-1">
                                            By completing this booking, you agree to our terms and conditions. 
                                            Cancellation policies may apply. Please review all details before confirming.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- ================
                                 TOMBOL SUBMIT BOOKING
                                ================ --}}
                            <button type="submit" class="w-full btn-primary-custom py-4 text-lg font-semibold rounded-lg flex items-center justify-center transition shadow-lg hover:shadow-xl">
                                <i class="fas fa-bookmark mr-3"></i>
                                Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- =====================
                 KOLOM KANAN: TOUR SUMMARY + CONTACT
                 ===================== --}}
            <div class="space-y-6">
                {{-- CARD RINGKASAN TOUR --}}
                <div class="card-custom p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-light mr-3">
                            <i class="fas fa-map-marked-alt text-primary"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Tour Summary</h2>
                    </div>
                    
                    {{-- Thumbnail tour jika tersedia --}}
                    @if($schedule->tour->thumbnail)
                    <img src="{{ asset('storage/' . $schedule->tour->thumbnail) }}" 
                         alt="{{ $schedule->tour->name }}" 
                         class="w-full h-48 object-cover rounded-lg mb-4 shadow-sm">
                    @else
                    <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                    </div>
                    @endif
                    
                    {{-- Info singkat tour (durasi, lokasi, tanggal, slot) --}}
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-clock text-primary mt-1 mr-3"></i>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Duration</label>
                                <p class="text-sm text-gray-900">{{ $schedule->tour->duration_days }} days</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-1 mr-3"></i>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Location</label>
                                <p class="text-sm text-gray-900">{{ $schedule->tour->location }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="far fa-calendar text-primary mt-1 mr-3"></i>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Schedule Date</label>
                                <p class="text-sm text-gray-900">{{ $schedule->date->format('F j, Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-users text-primary mt-1 mr-3"></i>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Available Slots</label>
                                <p class="text-sm text-gray-900">{{ $schedule->available_slots }} remaining</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Deskripsi tour --}}
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-600 mb-2">Tour Description</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $schedule->tour->description }}</p>
                    </div>
                </div>

                {{-- CARD BANTUAN / KONTAK --}}
                <div class="card-custom p-6 bg-blue-50 border border-blue-100">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-question-circle text-blue-500 text-lg mr-2"></i>
                        <h3 class="text-lg font-semibold text-blue-800">Need Help?</h3>
                    </div>
                    <div class="space-y-2 text-sm text-blue-700">
                        <p>If you have any questions or need assistance with your booking, our support team is here to help.</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-phone mr-2"></i>
                            <span>+6281234567890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>support@tourbooking.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ================================
    //  REAL-TIME PRICE CALCULATION
    //  - Update subtotal & total saat jumlah tamu berubah
    // ================================

    const guestsInput   = document.getElementById('guests');
    const guestsCount   = document.getElementById('guests-count');
    const subtotal      = document.getElementById('subtotal');
    const totalAmount   = document.getElementById('totalAmount');
    const pricePerGuest = {{ $schedule->tour->price }};

    function updatePriceCalculation() {
        // Ambil jumlah tamu, kalau NaN fallback ke 0
        const guests = parseInt(guestsInput.value) || 0;
        const total  = guests * pricePerGuest;
        
        // Update tampilan jumlah tamu dan subtotal dalam format Rupiah lokal
        guestsCount.textContent    = guests;
        subtotal.textContent       = 'Rp ' + total.toLocaleString('id-ID');
        totalAmount.textContent    = 'Rp ' + total.toLocaleString('id-ID');
        
        // Validasi maksimum tamu sesuai available_slots
        const maxGuests = {{ $schedule->available_slots }};
        if (guests > maxGuests) {
            guestsInput.value = maxGuests;
            // Panggil ulang supaya tampilan ikut menyesuaikan
            updatePriceCalculation();
        }
    }

    // Update ketika input jumlah tamu berubah
    guestsInput.addEventListener('input', updatePriceCalculation);
    
    // Inisialisasi saat halaman pertama kali dimuat
    updatePriceCalculation();
</script>
@endpush
