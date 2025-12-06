@extends('layouts.app')

@section('title', 'Booking Details - TourBooking')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('customer.bookings.index') }}"
                           class="mr-4 text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-[#010d4c]">Detail Booking</h1>
                            <p class="text-gray-600 mt-1">Booking #{{ $booking->id }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customer.bookings.index') }}"
                       class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center text-sm shadow-sm">
                        <i class="fas fa-list mr-2"></i>
                        Kembali ke Daftar Booking
                    </a>

                    @if($booking->status === 'pending')
                        <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini? Tindakan ini tidak dapat dibatalkan.')"
                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium flex items-center shadow-sm">
                                <i class="fas fa-times mr-2"></i>
                                Batalkan Booking
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- STATUS BANNER --}}
        <div class="card-custom p-6 mb-8 border-l-4
            @if($booking->status === 'confirmed') border-green-500 bg-green-50
            @elseif($booking->status === 'cancelled') border-red-500 bg-red-50
            @else border-yellow-400 bg-yellow-50 @endif">
            <div class="flex items-start gap-4">
                <div class="mt-1">
                    @if($booking->status === 'confirmed')
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    @elseif($booking->status === 'cancelled')
                        <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                    @else
                        <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-semibold
                        @if($booking->status === 'confirmed') text-green-800
                        @elseif($booking->status === 'cancelled') text-red-800
                        @else text-yellow-800 @endif">
                        @if($booking->status === 'pending')
                            Booking Menunggu Konfirmasi
                        @elseif($booking->status === 'confirmed')
                            Booking Dikonfirmasi
                        @else
                            Booking Dibatalkan
                        @endif
                    </h3>
                    <p class="mt-1 text-sm
                        @if($booking->status === 'confirmed') text-green-700
                        @elseif($booking->status === 'cancelled') text-red-700
                        @else text-yellow-700 @endif">
                        @if($booking->status === 'pending')
                            Booking Anda sedang menunggu konfirmasi dari admin. Anda akan menerima notifikasi via WhatsApp ketika booking dikonfirmasi.
                        @elseif($booking->status === 'confirmed')
                            Booking Anda telah dikonfirmasi! Silakan siapkan diri untuk tour pada tanggal yang telah ditentukan.
                        @else
                            Booking ini telah dibatalkan. Jika ini adalah kesalahan, silakan hubungi customer service.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- INFORMASI TOUR --}}
                <div class="card-custom p-6">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#eef2ff] mr-4">
                            <i class="fas fa-map-marked-alt text-[#4556a6] text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#010d4c]">Informasi Tour</h2>
                            <p class="text-gray-600 text-sm">Detail paket tour yang dipesan</p>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-6">
                        {{-- Thumbnail --}}
                        @if($booking->schedule->tour->thumbnail)
                            <img src="{{ asset('storage/' . $booking->schedule->tour->thumbnail) }}"
                                 alt="{{ $booking->schedule->tour->name }}"
                                 class="w-full md:w-32 h-32 object-cover rounded-lg shadow-sm flex-shrink-0">
                        @else
                            <div class="w-full md:w-32 h-32 rounded-lg bg-[#eef2ff] flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marked-alt text-[#4556a6] text-2xl"></i>
                            </div>
                        @endif

                        {{-- Info Grid --}}
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Nama Tour</label>
                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ $booking->schedule->tour->name }}
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Lokasi</label>
                                <p class="mt-1 text-sm text-gray-900 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#4556a6] text-xs"></i>
                                    {{ $booking->schedule->tour->location }}
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Tanggal Tour</label>
                                <p class="mt-1 text-sm text-gray-900 flex items-center">
                                    <i class="far fa-calendar mr-2 text-[#4556a6] text-xs"></i>
                                    {{ $booking->schedule->date->format('d M Y') }}
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Durasi</label>
                                <p class="mt-1 text-sm text-gray-900 flex items-center">
                                    <i class="far fa-clock mr-2 text-[#4556a6] text-xs"></i>
                                    {{ $booking->schedule->tour->duration_days }} hari
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Harga per Orang</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    Rp {{ number_format($booking->schedule->tour->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Slot Tersisa</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $booking->schedule->available_slots }} slot
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($booking->schedule->tour->description)
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-600">Deskripsi Tour</label>
                            <p class="mt-2 text-sm text-gray-700 leading-relaxed">
                                {{ $booking->schedule->tour->description }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- INFORMASI KONTAK --}}
                <div class="card-custom p-6">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#eef2ff] mr-4">
                            <i class="fas fa-user-circle text-[#4556a6] text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#010d4c]">Informasi Kontak</h2>
                            <p class="text-gray-600 text-sm">Data pemesan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->customer_name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Nomor Telepon</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->customer_phone }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->user->email }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Status Akun</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($booking->user->email_verified_at)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Belum Verifikasi
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="space-y-6">

                {{-- DETAIL BOOKING --}}
                <div class="card-custom p-6">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#eef2ff] mr-4">
                            <i class="fas fa-receipt text-[#4556a6] text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#010d4c]">Detail Booking</h2>
                            <p class="text-gray-600 text-sm">Informasi pemesanan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status Booking</label>
                            <div class="mt-2">
                                <span class="status-badge status-{{ $booking->status }}">
                                    @if($booking->status === 'pending')
                                        <i class="fas fa-clock mr-2"></i> Menunggu Konfirmasi
                                    @elseif($booking->status === 'confirmed')
                                        <i class="fas fa-check-circle mr-2"></i> Dikonfirmasi
                                    @else
                                        <i class="fas fa-times-circle mr-2"></i> Dibatalkan
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Tanggal Booking</label>
                            <p class="mt-1 text-sm text-gray-900 flex items-center">
                                <i class="far fa-calendar-alt mr-2 text-[#4556a6] text-xs"></i>
                                {{ $booking->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Jumlah Peserta</label>
                            <p class="mt-1 text-sm text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-[#4556a6] text-xs"></i>
                                {{ $booking->guests }} orang
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Total Harga</label>
                            <p class="mt-1 text-2xl font-bold text-[#4556a6]">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- RINCIAN HARGA --}}
                <div class="card-custom p-6">
                    <h3 class="text-lg font-semibold text-[#010d4c] mb-4">Rincian Harga</h3>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex justify-between">
                            <span>{{ $booking->guests }} orang × Rp {{ number_format($booking->schedule->tour->price, 0, ',', '.') }}</span>
                            <span>Rp {{ number_format($booking->guests * $booking->schedule->tour->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya layanan</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between font-semibold text-gray-900">
                                <span>Total</span>
                                <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CATATAN KHUSUS --}}
                @if($booking->notes)
                    <div class="card-custom p-6 bg-[#eef2ff] border border-[#d7ddff]">
                        <div class="flex items-start">
                            <i class="fas fa-sticky-note text-[#4556a6] mt-1 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-medium text-[#4556a6] mb-1">Catatan Khusus</h3>
                                <p class="text-sm text-[#2c396d]">{{ $booking->notes }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- HELP CARD --}}
                <div class="card-custom p-6 bg-gray-50 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-question-circle text-gray-500 text-lg mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Butuh Bantuan?</h3>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p>Jika Anda memiliki pertanyaan tentang booking ini, hubungi customer service kami.</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-phone mr-2 text-[#4556a6]"></i>
                            <span>+62 812-3456-7890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-[#4556a6]"></i>
                            <span>support@tourbooking.com</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- UPCOMING INFO --}}
        @if($booking->status === 'confirmed' && $booking->schedule->date->isFuture())
            <div class="card-custom p-6 mt-6 border border-green-200 bg-green-50">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-green-500 text-xl mt-1"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Tour Akan Datang</h3>
                        <p class="text-green-700 mt-1 text-sm">
                            Tour Anda akan berlangsung pada
                            <strong>{{ $booking->schedule->date->format('d M Y') }}</strong>.
                            Pastikan untuk mempersiapkan segala kebutuhan Anda sebelum keberangkatan.
                        </p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border: 1px solid transparent;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #d97706;
        border-color: #fcd34d;
    }

    .status-confirmed {
        background-color: #d1fae5;
        color: #059669;
        border-color: #34d399;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #dc2626;
        border-color: #fca5a5;
    }
</style>
@endpush
