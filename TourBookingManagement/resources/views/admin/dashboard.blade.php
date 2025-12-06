@extends('layouts.app')

@section('title', 'Admin Dashboard - TourBooking')

@section('content')
@php
    // Ambil user yang sedang login untuk sapaan di header
    $user = Auth::user();
@endphp

<div class="container mx-auto px-4 py-8">
    {{-- =======================
        HEADER DASHBOARD
        - Judul + badge "Admin Dashboard"
        - Deskripsi singkat
        - Tanggal hari ini (now())
    ======================== --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[#010d4c]">
                        Welcome back, {{ $user->name }}! 👋
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border border-gray-200 text-gray-600">
                        Admin Dashboard
                    </span>
                </div>
                <p class="text-gray-600 mt-2">
                    Overview of your tour booking system and recent activity.
                </p>
            </div>

            {{-- Tanggal sekarang (format: Senin, December 6, 2025) --}}
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <i class="fas fa-calendar-day text-primary"></i>
                <span>{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- =======================
        STAT CARDS (OVERVIEW)
        1. Total Tours
        2. Total Bookings
        3. Pending Bookings
        4. Revenue
    ======================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Tours: semua tour baik aktif maupun non-aktif --}}
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-white text-sm font-medium">Total Tours</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $totalTours ?? 0 }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-white text-sm flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    All active & inactive tours
                </span>
            </div>
        </div>

        {{-- Total Bookings: jumlah semua booking dari pelanggan --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Bookings</p>
                    <h3 class="text-2xl font-bold text-primary mt-1">{{ $totalBookings ?? 0 }}</h3>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="fas fa-bookmark text-green-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">
                    All bookings from customers
                </span>
            </div>
        </div>

        {{-- Pending: booking yang status-nya masih menunggu konfirmasi admin --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending</p>
                    <h3 class="text-2xl font-bold text-warning mt-1">{{ $pendingBookings ?? 0 }}</h3>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">
                    Need your confirmation
                </span>
            </div>
        </div>

        {{-- Revenue: total pendapatan dari booking yang sudah dikonfirmasi --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Revenue</p>
                    <h3 class="text-2xl font-bold text-primary mt-1">
                        Rp {{ number_format($revenue ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-purple-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">
                    From confirmed bookings
                </span>
            </div>
        </div>
    </div>

    {{-- =======================
        GRID UTAMA
        - Left: Recent Bookings
        - Right: Quick Actions + Tips
    ======================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- =======================
            RECENT BOOKINGS (Admin view)
            Menampilkan beberapa booking terakhir
        ======================== --}}
        <div class="lg:col-span-2">
            <div class="card-custom">
                {{-- Header card bookings --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-[#010d4c]">Recent Bookings</h2>
                    <a href="{{ route('admin.bookings.index') }}"
                       class="text-primary hover:text-primary-dark text-sm font-medium flex items-center">
                        View All
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

                {{-- Tabel daftar booking terbaru --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Tour
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Nama & kontak customer --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $booking->customer_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $booking->customer_phone }}
                                        </div>
                                    </td>

                                    {{-- Nama tour (via relasi schedule -> tour) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $booking->schedule->tour->name ?? '-' }}
                                    </td>

                                    {{-- Tanggal jadwal tour (optional untuk handle null schedule/date) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ optional($booking->schedule->date)->format('M d, Y') ?? '-' }}
                                    </td>

                                    {{-- Status booking dengan badge warna beda: pending/confirmed/cancelled --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge status-{{ $booking->status }} text-xs">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                {{-- Kalau belum ada booking sama sekali --}}
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                        No bookings found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- =======================
            QUICK ACTIONS (shortcut admin)
            + Info card kecil
        ======================== --}}
        <div class="space-y-6">
            {{-- Shortcut ke halaman penting (Tours, Schedules, Bookings) --}}
            <div class="card-custom p-6">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-light mr-3">
                        <i class="fas fa-bolt text-primary text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-[#010d4c]">Quick Actions</h2>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.tours.create') }}"
                       class="block w-full btn-primary-custom text-center py-3 rounded-lg flex items-center justify-center transition">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Tour
                    </a>
                    <a href="{{ route('admin.schedules.create') }}"
                       class="block w-full border border-gray-300 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-50 flex items-center justify-center transition">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Add Schedule
                    </a>
                    <a href="{{ route('admin.tours.index') }}"
                       class="block w-full border border-gray-300 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-50 flex items-center justify-center transition">
                        <i class="fas fa-list mr-2"></i>
                        Manage Tours
                    </a>
                    <a href="{{ route('admin.bookings.index') }}"
                       class="block w-full border border-gray-300 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-50 flex items-center justify-center transition">
                        <i class="fas fa-bookmark mr-2"></i>
                        Manage Bookings
                    </a>
                </div>
            </div>

            {{-- Info / tips kecil untuk admin --}}
            <div class="card-custom p-6 bg-blue-50 border border-blue-100">
                <div class="flex items-center mb-3">
                    <i class="fas fa-info-circle text-blue-500 text-lg mr-2"></i>
                    <h3 class="text-lg font-semibold text-blue-800">Admin Tips</h3>
                </div>
                <p class="text-sm text-blue-700">
                    Keep your tour schedules and availability updated to ensure a smooth booking experience for customers.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Kartu statistik biru di bagian atas */
    .stat-card {
        background: #010d4c;
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Badge status booking (pending / confirmed / cancelled) */
    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        width: fit-content;
    }

    /* Warna status: pending (kuning) */
    .status-pending {
        background-color: #fef3c7;
        color: #d97706;
    }

    /* Warna status: confirmed (hijau) */
    .status-confirmed {
        background-color: #d1fae5;
        color: #059669;
    }

    /* Warna status: cancelled (merah) */
    .status-cancelled {
        background-color: #fee2e2;
        color: #dc2626;
    }
</style>
@endpush
