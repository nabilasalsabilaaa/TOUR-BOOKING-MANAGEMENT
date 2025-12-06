
@extends('layouts.app')

@section('title', $tour->name . ' - Tour Detail')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Breadcrumb / Back --}}
    <div class="mb-4">
        <a href="{{ route('tours.index') }}" class="text-sm text-gray-500 hover:text-primary-600 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2 text-xs"></i>
            Back to Tours
        </a>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="md:flex">
            {{-- Thumbnail --}}
            <div class="md:w-1/3">
                @if($tour->thumbnail)
                    <img src="{{ asset('storage/' . $tour->thumbnail) }}"
                         alt="{{ $tour->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full min-h-[220px] bg-indigo-50 flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-4xl text-indigo-400"></i>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="md:w-2/3 p-6 md:p-8">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        {{ $tour->name }}
                    </h1>

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

                <p class="text-gray-500 mb-4 flex items-center text-sm">
                    <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                    {{ $tour->location ?? 'Location not specified' }}
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs uppercase mb-1">Price</p>
                        <p class="font-semibold text-gray-900">
                            Rp {{ number_format($tour->price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs uppercase mb-1">Duration</p>
                        <p class="font-semibold text-gray-900">
                            {{ $tour->duration_days }} days
                        </p>
                    </div>
                    @if($tour->capacity)
                        <div>
                            <p class="text-gray-400 text-xs uppercase mb-1">Capacity</p>
                            <p class="font-semibold text-gray-900">
                                {{ $tour->capacity }} people
                            </p>
                        </div>
                    @endif
                    @isset($tour->bookings_count)
                        <div>
                            <p class="text-gray-400 text-xs uppercase mb-1">Total Bookings</p>
                            <p class="font-semibold text-gray-900">
                                {{ $tour->bookings_count }}
                            </p>
                        </div>
                    @endisset
                </div>

                {{-- CTA utama kalau ada jadwal aktif --}}
                @if($tour->schedules->count())
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('customer.bookings.create', $tour->schedules->first()) }}"
                           class="inline-flex items-center px-5 py-2.5 rounded-full bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition">
                            <i class="fas fa-ticket-alt mr-2 text-xs"></i>
                            Book Nearest Schedule
                        </a>

                        <p class="text-xs text-gray-500">
                            Jadwal terdekat: 
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($tour->schedules->first()->date)->format('d M Y') }}
                            </span>
                        </p>
                    </div>
                @else
                    <p class="text-sm text-red-500 font-medium">
                        Saat ini belum ada jadwal aktif untuk tour ini.
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- List Jadwal --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">
                Available Schedules
            </h2>
        </div>

        @if($tour->schedules->count())
            <div class="space-y-3">
                @foreach($tour->schedules as $schedule)
                    <div class="flex flex-col md:flex-row md:items-center justify-between border border-gray-100 rounded-xl px-4 py-3 hover:border-primary/30 transition">
                        <div class="mb-2 md:mb-0">
                            <p class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Seats left: {{ $schedule->available_seats ?? '-' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <p class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($schedule->price ?? $tour->price, 0, ',', '.') }}
                            </p>
                            @auth
                                <a href="{{ route('customer.bookings.create', $schedule) }}"
                                   class="inline-flex items-center px-4 py-2 rounded-full bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">
                                    <i class="fas fa-ticket-alt mr-2 text-[11px]"></i>
                                    Book This Date
                                </a>
                            @else
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
            <p class="text-sm text-gray-500">
                Belum ada jadwal yang tersedia untuk tour ini.
            </p>
        @endif
    </div>
</div>
@endsection
