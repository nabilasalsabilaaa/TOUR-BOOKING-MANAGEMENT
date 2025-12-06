@extends('layouts.app')

@section('title', 'Manage Tours - TourBooking Admin')

@section('content')
@php
    // Cek role untuk nentuin tampilan kolom "Actions" vs "Booking"
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp

<div class="container mx-auto px-4 py-8">
    {{-- =========================
        HEADER HALAMAN
        - Judul
        - Deskripsi singkat
        - Tombol "Add New Tour"
    ========================== --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-900">Manage Tours</h1>
                <p class="text-gray-600 mt-2">Create and manage tour packages</p>
            </div>

            {{-- Tombol untuk buat tour baru --}}
            <div class="flex space-x-3">
                <a href="{{ route('admin.tours.create') }}" class="btn-primary-custom px-4 py-2 text-white rounded-lg flex items-center transition">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Tour
                </a>
            </div>
        </div>
    </div>

    {{-- =========================
        STATISTICS CARDS
        - Total tours
        - Active tours
        - Total revenue
        - Average price
    ========================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Tours --}}
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-white text-sm font-medium">Total Tours</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $tours->total() }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-white text-sm flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    All tours in system
                </span>
            </div>
        </div>
        
        {{-- Active Tours (is_active = true) --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Tours</p>
                    <h3 class="text-2xl font-bold text-success mt-1">
                        {{ $tours->where('is_active', true)->count() }}
                    </h3>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="fas fa-eye text-green-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">Visible to customers</span>
            </div>
        </div>
        
        {{-- Total Revenue dari semua booking tours (butuh eager load aggregate) --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-primary mt-1">
                        Rp {{ number_format($tours->sum('bookings_sum_total_price') ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-wallet text-blue-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">From all bookings</span>
            </div>
        </div>
        
        {{-- Rata-rata harga tour --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Avg. Price</p>
                    <h3 class="text-2xl font-bold text-warning mt-1">
                        Rp {{ number_format($tours->avg('price') ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <i class="fas fa-tag text-yellow-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">Average tour price</span>
            </div>
        </div>
    </div>

    {{-- =========================
        TABEL DAFTAR TOUR
    ========================== --}}
    <div class="card-custom">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        {{-- Info lokasi tour --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Location
                        </th>

                        {{-- Harga + durasi tour --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price & Duration
                        </th>

                        {{-- Statistik per tour (jumlah booking + revenue per tour) --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statistics
                        </th>

                        {{-- Status tour (Active / Inactive) --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>

                        {{-- Untuk ADMIN: label "Actions", untuk non-admin, nanti ada kolom Booking juga --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>

                        @if (!$isAdmin)
                            {{-- Kolom booking khusus jika user bukan admin (mis. customer) --}}
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Booking
                            </th>
                        @endif
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tours as $tour)
                    <tr class="table-row-hover">
                        {{-- ========== LOCATION ========== --}}
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-primary text-sm"></i>
                                {{ $tour->location ?? 'Not specified' }}
                            </div>
                        </td>

                        {{-- ========== PRICE & DURATION ========== --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($tour->price, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $tour->duration_days }} days
                            </div>
                            @if($tour->capacity)
                            <div class="text-xs text-gray-400 mt-1 flex items-center">
                                <i class="fas fa-users mr-1"></i>
                                Capacity: {{ $tour->capacity }}
                            </div>
                            @endif
                        </td>

                        {{-- ========== STATISTICS PER TOUR ========== --}}
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-primary"></i>
                                {{ $tour->bookings_count ?? 0 }} bookings
                            </div>
                            @if($tour->bookings_count > 0)
                            <div class="text-xs text-gray-500 mt-1">
                                Rp {{ number_format($tour->bookings_sum_total_price ?? 0, 0, ',', '.') }} revenue
                            </div>
                            @endif
                        </td>

                        {{-- ========== STATUS BADGE ========== --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tour->is_active)
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 flex items-center w-fit">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                Active
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 flex items-center w-fit">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                Inactive
                            </span>
                            @endif
                        </td>

                        @if ($isAdmin)
                            {{-- ========== ADMIN: AKSI CRUD ========== --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    {{-- Edit detail tour --}}
                                    <a href="{{ route('admin.tours.edit', $tour) }}" 
                                       class="text-primary hover:text-primary-dark transition-colors"
                                       title="Edit Tour">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Tambah schedule baru untuk tour ini --}}
                                    <a href="{{ route('admin.schedules.create') }}?tour={{ $tour->id }}" 
                                       class="text-info hover:text-blue-800 transition-colors"
                                       title="Add Schedule">
                                        <i class="fas fa-calendar-plus"></i>
                                    </a>

                                    {{-- Lihat daftar schedule untuk tour ini --}}
                                    <a href="{{ route('admin.schedules.index') }}?tour={{ $tour->id }}" 
                                       class="text-success hover:text-green-800 transition-colors"
                                       title="View Schedules">
                                        <i class="fas fa-list"></i>
                                    </a>

                                    {{-- Hapus tour (beserta schedules & bookings terkait) --}}
                                    <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-error hover:text-red-800 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this tour? This will also delete all related schedules and bookings.')"
                                                title="Delete Tour">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @else
                            {{-- ========== NON-ADMIN (CUSTOMER): TOMBOL BOOKING SAJA ========== --}}
                            {{-- Kolom "Actions" di header masih ada, tapi kosongan di sini.
                                Interaksi utama non-admin ada di kolom "Booking" di bawah. --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{-- Bisa diisi link "View Detail" kalau mau --}}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('tours.show', $tour) }}" 
                                   class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">
                                    <i class="fas fa-ticket-alt mr-2 text-[11px]"></i>
                                    Book This Tour
                                </a>
                            </td>
                        @endif
                    </tr>
                    @empty
                    {{-- Kalau belum ada tour sama sekali --}}
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-map-marked-alt text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No tours found</h3>
                                <p class="text-gray-500 mb-4">Get started by creating your first tour package.</p>
                                <a href="{{ route('admin.tours.create') }}" 
                                   class="btn-primary-custom px-4 py-2 text-white rounded-lg flex items-center transition">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create New Tour
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- =========================
            PAGINATION
        ========================== --}}
        @if($tours->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $tours->firstItem() }} to {{ $tours->lastItem() }} of {{ $tours->total() }} results
            </div>
            <div class="flex space-x-2">
                {{ $tours->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --tb-primary: #010d4c;
        --tb-primary-soft: #4556a6;
        --tb-primary-dark: #2c396d;
        --tb-accent: #ffb524;
        --tb-accent-soft: #f8ca5b;
        --tb-bg-soft: #eef2ff;
    }

    /* Kartu statistik biru di bagian atas */
    .stat-card {
        background: #010d4c;
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Card wrapper putih untuk tabel & info */
    .card-custom {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    /* Tombol utama biru gradien */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--tb-primary) 0%, var(--tb-primary-dark) 100%);
        color: #ffffff;
    }

    .btn-primary-custom:hover {
        filter: brightness(1.03);
        transform: translateY(-1px);
        box-shadow: 0 18px 40px rgba(1, 13, 76, 0.35);
    }

    /* Hover effect untuk tiap baris tabel */
    .table-row-hover {
        transition: all 0.18s ease;
    }

    .table-row-hover:hover {
        background-color: var(--tb-bg-soft);
        transform: translateY(-1px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }

    .bg-primary-light {
        background-color: var(--tb-bg-soft);
    }

    .text-primary {
        color: var(--tb-primary-soft);
    }

    .text-error {
        color: #b91c1c;
    }
</style>
@endpush

@push('scripts')
<script>
    // Placeholder untuk fitur filter/sort ke depannya
    // (Saat ini belum ada elemen input/filter di blade ini, jadi hanya skeleton.)
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[type="text"]');
        const statusFilter = document.querySelectorAll('select')[0];
        const sortFilter = document.querySelectorAll('select')[1];
    });
</script>
@endpush
