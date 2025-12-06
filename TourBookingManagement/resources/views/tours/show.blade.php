{{-- 
    Tour Detail Page
    File: resources/views/tours/show.blade.php
    Controller: TourController@show (mengirim data $tour dengan relasi schedules)
    Routes: GET /tours/{tour} -> tours.show
    Purpose: Menampilkan detail lengkap sebuah tour termasuk jadwal yang tersedia
--}}

@extends('layouts.app')

@section('title', $tour->name . ' - Tour Detail')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- ==================== BREADCRUMB / BACK NAVIGATION ==================== --}}
    <div class="mb-4">
        <a href="{{ route('tours.index') }}" class="text-sm text-gray-500 hover:text-primary-600 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2 text-xs"></i>
            Back to Tours
        </a>
    </div>

    {{-- ==================== MAIN TOUR CARD ==================== --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="md:flex">
            {{-- LEFT SECTION: TOUR THUMBNAIL --}}
            <div class="md:w-1/3">
                @if($tour->thumbnail)
                    {{-- Menampilkan thumbnail tour jika ada --}}
                    <img src="{{ asset('storage/' . $tour->thumbnail) }}"
                         alt="{{ $tour->name }}"
                         class="w-full h-full object-cover">
                @else
                    {{-- Placeholder jika tidak ada thumbnail --}}
                    <div class="w-full h-full min-h-[220px] bg-indigo-50 flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-4xl text-indigo-400"></i>
                    </div>
                @endif
            </div>

            {{-- RIGHT SECTION: TOUR INFORMATION --}}
            <div class="md:w-2/3 p-6 md:p-8">
                {{-- Tour name dan status badge --}}
                <div class="flex items-center justify-between gap-3 mb-3">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        {{ $tour->name }}
                    </h1>

                    {{-- Status badge: Active/Inactive --}}
                    @if($tour->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <span class="w-2 h-2 rounded-full bg-red-500 mr-1.5"></span>
                            Inactive
                        </span>
                    @endif
                </div>

                {{-- Tour location --}}
                <p class="text-gray-500 mb-4 flex items-center text-sm">
                    <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                    {{ $tour->location ?? 'Location not specified' }}
                </p>

                {{-- Tour details grid: Price, Duration, Capacity, Bookings Count --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
                    {{-- Price --}}
                    <div>
                        <p class="text-gray-400 text-xs uppercase mb-1">Price</p>
                        <p class="font-semibold text-gray-900">
                            Rp {{ number_format($tour->price, 0, ',', '.') }}
                        </p>
                    </div>
                    {{-- Duration --}}
                    <div>
                        <p class="text-gray-400 text-xs uppercase mb-1">Duration</p>
                        <p class="font-semibold text-gray-900">
                            {{ $tour->duration_days }} days
                        </p>
                    </div>
                    {{-- Capacity (if available) --}}
                    @if($tour->capacity)
                        <div>
                            <p class="text-gray-400 text-xs uppercase mb-1">Capacity</p>
                            <p class="font-semibold text-gray-900">
                                {{ $tour->capacity }} people
                            </p>
                        </div>
                    @endif
                    {{-- Total Bookings (if available) --}}
                    @isset($tour->bookings_count)
                        <div>
                            <p class="text-gray-400 text-xs uppercase mb-1">Total Bookings</p>
                            <p class="font-semibold text-gray-900">
                                {{ $tour->bookings_count }}
                            </p>
                        </div>
                    @endisset
                </div>

                {{-- PRIMARY CALL TO ACTION: Book Nearest Schedule --}}
                @if($tour->schedules->count())
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Button untuk booking jadwal terdekat --}}
                        <a href="{{ route('customer.bookings.create', $tour->schedules->first()) }}"
                           class="inline-flex items-center px-5 py-2.5 rounded-full bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition">
                            <i class="fas fa-ticket-alt mr-2 text-xs"></i>
                            Book Nearest Schedule
                        </a>

                        {{-- Menampilkan tanggal jadwal terdekat --}}
                        <p class="text-xs text-gray-500">
                            Jadwal terdekat: 
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($tour->schedules->first()->date)->format('d M Y') }}
                            </span>
                        </p>
                    </div>
                @else
                    {{-- Message jika tidak ada jadwal --}}
                    <p class="text-sm text-red-500 font-medium">
                        Saat ini belum ada jadwal aktif untuk tour ini.
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- ==================== SCHEDULES LIST SECTION ==================== --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">
                Available Schedules
            </h2>
        </div>

        {{-- List jadwal jika ada --}}
        @if($tour->schedules->count())
            <div class="space-y-3">
                {{-- Loop melalui setiap schedule --}}
                @foreach($tour->schedules as $schedule)
                    <div class="flex flex-col md:flex-row md:items-center justify-between border border-gray-100 rounded-xl px-4 py-3 hover:border-primary/30 transition">
                        {{-- Schedule info: Date dan available seats --}}
                        <div class="mb-2 md:mb-0">
                            <p class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Seats left: {{ $schedule->available_seats ?? '-' }}
                            </p>
                        </div>

                        {{-- Schedule price dan booking button --}}
                        <div class="flex items-center gap-3">
                            {{-- Price untuk schedule ini --}}
                            <p class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($schedule->price ?? $tour->price, 0, ',', '.') }}
                            </p>
                            
                            {{-- Conditional booking button berdasarkan auth status --}}
                            @auth
                                {{-- Untuk user yang sudah login --}}
                                <a href="{{ route('customer.bookings.create', $schedule) }}"
                                   class="inline-flex items-center px-4 py-2 rounded-full bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">
                                    <i class="fas fa-ticket-alt mr-2 text-[11px]"></i>
                                    Book This Date
                                </a>
                            @else
                                {{-- Untuk guest: arahkan ke login --}}
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center px-4 py-2 rounded-full border border-primary text-primary text-xs font-semibold hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-sign-in-alt mr-2 text-[11px]"></i>
                                    Login to Book
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Message jika tidak ada jadwal --}}
            <p class="text-sm text-gray-500">
                Belum ada jadwal yang tersedia untuk tour ini.
            </p>
        @endif
    </div>
</div>
@endsection