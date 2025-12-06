@extends('layouts.app')

@section('title', 'Manage Bookings')

@section('content')

{{-- =========================
     CUSTOM STYLE SECTION
   ========================== --}}
<style>
    :root {
        --tb-primary: #010d4c;
        --tb-primary-soft: #4556a6;
        --tb-primary-dark: #2c396d;
        --tb-accent: #ffb524;
        --tb-accent-soft: #f8ca5b;
        --tb-bg-soft: #eef2ff;
    }

    /* Kartu statistik utama di bagian atas */
    .stat-card {
        background: #010d4c;
        border-radius: 14px;
        padding: 1.5rem;
        color: #fff;
        box-shadow: 0 18px 35px rgba(1, 13, 76, 0.25);
        position: relative;
        overflow: hidden;
    }

    .stat-card > * {
        position: relative;
        z-index: 1;
    }

    .stat-card-icon {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 999px;
    }

    /* Kartu filter */
    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 1.5rem;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    /* Hover untuk baris tabel */
    .table-row-hover {
        transition: all 0.2s ease;
    }

    .table-row-hover:hover {
        background-color: var(--tb-bg-soft);
        transform: translateY(-1px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }

    /* Kartu kontainer utama (table wrapper) */
    .card-custom {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 1.5rem;
    }

    /* Tombol outline umum */
    .btn-outline {
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.7);
        background: #ffffff;
    }

    .btn-outline:hover {
        border-color: var(--tb-primary-soft);
        background: #f9fafb;
    }

    /* Tombol utama (Apply Filters) */
    .btn-primary-modern {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--tb-accent) 0%, var(--tb-accent-soft) 100%);
        color: #010d4c;
        font-weight: 600;
        box-shadow: 0 14px 30px rgba(248, 202, 91, 0.45);
    }

    .btn-primary-modern:hover {
        filter: brightness(0.97);
        box-shadow: 0 20px 45px rgba(248, 202, 91, 0.55);
        transform: translateY(-1px);
    }

    /* Badge status */
    .status-pending {
        background: rgba(248, 202, 91, 0.12);
        color: #92400e;
        border: 1px solid rgba(248, 202, 91, 0.7);
        border-radius: 999px;
    }

    .status-confirmed {
        background: rgba(34, 197, 94, 0.12);
        color: #166534;
        border: 1px solid rgba(34, 197, 94, 0.7);
        border-radius: 999px;
    }

    .status-cancelled {
        background: rgba(239, 68, 68, 0.08);
        color: #b91c1c;
        border: 1px solid rgba(239, 68, 68, 0.65);
        border-radius: 999px;
    }

    .status-completed {
        background: rgba(37, 99, 235, 0.08);
        color: #1d4ed8;
        border: 1px solid rgba(37, 99, 235, 0.55);
        border-radius: 999px;
    }

    .status-pill {
        padding: 0.25rem 0.75rem;
        font-size: 0.72rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Shell input (search) */
    .input-shell {
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.7);
        background: #ffffff;
    }

    .input-shell:focus-within {
        border-color: var(--tb-primary-soft);
        box-shadow: 0 0 0 1px rgba(69, 86, 166, 0.35);
    }

    /* Spinner kecil untuk tombol Apply */
    .spinner {
        width: 14px;
        height: 14px;
        border-radius: 999px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-top-color: rgba(255, 255, 255, 1);
        animation: spin 0.6s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<div class="container mx-auto px-4 py-8">

    {{-- =========================
         PAGE HEADER
       ========================== --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Manage Bookings</h1>
                <p class="text-slate-500 mt-2">
                    Monitor and manage all customer bookings from a single modern dashboard.
                </p>
            </div>
        </div>
    </div>

    {{-- =========================
         STATISTICS CARDS
       ========================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total bookings (menggunakan total dari paginator) --}}
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium tracking-wide opacity-80">Total Bookings</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $bookings->total() }}</h3>
                    <p class="text-xs mt-2 opacity-80">All bookings in the system</p>
                </div>
                <div class="stat-card-icon p-3">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Pending bookings (dihitung dari collection di halaman ini) --}}
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Pending</p>
                    <h3 class="text-2xl font-bold text-amber-500 mt-1">
                        {{ $bookings->where('status', 'pending')->count() }}
                    </h3>
                </div>
                <div class="bg-amber-50 p-3 rounded-xl">
                    <i class="fas fa-clock text-amber-500 text-xl"></i>
                </div>
            </div>
            <p class="text-slate-400 text-sm mt-4">Need your confirmation</p>
        </div>

        {{-- Confirmed bookings --}}
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Confirmed</p>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                        {{ $bookings->where('status', 'confirmed')->count() }}
                    </h3>
                </div>
                <div class="bg-emerald-50 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                </div>
            </div>
            <p class="text-slate-400 text-sm mt-4">Upcoming active tours</p>
        </div>

        {{-- Revenue (penjumlahan total_price dari booking di halaman ini) --}}
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Revenue</p>
                    <h3 class="text-2xl font-bold text-[var(--tb-primary)] mt-1">
                        Rp {{ number_format($bookings->sum('total_price'), 0, ',', '.') }}
                    </h3>
                </div>
                <div class="bg-[var(--tb-bg-soft)] p-3 rounded-xl">
                    <i class="fas fa-wallet text-[var(--tb-primary-soft)] text-xl"></i>
                </div>
            </div>
            <p class="text-emerald-600 text-sm mt-4 flex items-center gap-1">
                <i class="fas fa-arrow-up text-xs"></i>
                Total revenue from all bookings
            </p>
        </div>
    </div>

    {{-- =========================
         FILTERS & SEARCH FORM
       ========================== --}}
    <form method="GET"
          action="{{ route('admin.bookings.index') }}"
          class="filter-card mb-6 p-5 rounded-xl shadow-sm border border-slate-200 bg-white">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            {{-- LEFT: Search + Status + Date --}}
            <div class="flex flex-col sm:flex-row gap-4">

                {{-- Search input --}}
                <div class="relative input-shell pl-10 pr-4 py-2 flex items-center w-full md:w-64 rounded-full border border-slate-300 bg-white">
                    <i class="fas fa-search absolute left-3 text-slate-400 text-sm"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search bookings..."
                        class="w-full bg-transparent border-0 focus:ring-0 text-sm text-slate-700 placeholder-slate-400"
                    >
                </div>

                {{-- Status filter --}}
                <select
                    name="status"
                    class="border border-slate-300 rounded-full px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-[var(--tb-primary-soft)] focus:border-[var(--tb-primary-soft)]">
                    <option value="">All Status</option>
                    <option value="pending"   {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>

                {{-- Date filter (schedule date) --}}
                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="border border-slate-300 rounded-full px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-[var(--tb-primary-soft)] focus:border-[var(--tb-primary-soft)]"
                >
            </div>

            {{-- RIGHT: Buttons (Apply & Reset) --}}
            <div class="flex items-center space-x-2">

                {{-- Apply filters (submit form) --}}
                <button
                    type="submit"
                    class="btn-primary-modern px-4 py-2 text-sm flex items-center gap-2 rounded-full transition-all duration-200">
                    <i class="fas fa-filter"></i>
                    Apply Filters
                </button>

                {{-- Reset filters (kembali ke index tanpa query string) --}}
                <a
                    href="{{ route('admin.bookings.index') }}"
                    class="px-4 py-2 bg-white border border-slate-300 rounded-full text-sm text-slate-700 hover:bg-slate-50 transition-all duration-200 inline-flex items-center">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- =========================
         BOOKINGS TABLE
       ========================== --}}
    <div class="card-custom">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Booking Details</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tour & Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Guests</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($bookings as $booking)
                        <tr class="table-row-hover">
                            {{-- Kolom: Booking Details & Customer --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-[var(--tb-bg-soft)] flex items-center justify-center">
                                        <i class="fas fa-receipt text-[var(--tb-primary-soft)] text-sm"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-slate-900">#{{ $booking->id }}</div>
                                        <div class="text-sm text-slate-500">{{ $booking->customer_name }}</div>
                                        <div class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                            <i class="fas fa-phone"></i>{{ $booking->customer_phone }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom: Tour & Date --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900">
                                    {{ $booking->schedule->tour->name }}
                                </div>
                                <div class="text-sm text-slate-500 flex items-center mt-1">
                                    <i class="far fa-calendar mr-2 text-[var(--tb-primary-soft)]"></i>
                                    {{ $booking->schedule->date->format('M d, Y') }}
                                </div>
                            </td>

                            {{-- Kolom: Guests --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 flex items-center">
                                    <i class="fas fa-users mr-2 text-[var(--tb-primary-soft)]"></i>
                                    {{ $booking->guests }} guests
                                </div>
                            </td>

                            {{-- Kolom: Amount --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-900">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </div>
                            </td>

                            {{-- Kolom: Status (badge dinamis) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = 'status-pending';
                                    if ($booking->status === 'confirmed') $statusClass = 'status-confirmed';
                                    elseif ($booking->status === 'cancelled') $statusClass = 'status-cancelled';
                                    elseif ($booking->status === 'completed') $statusClass = 'status-completed';
                                @endphp
                                <span class="status-pill {{ $statusClass }}">
                                    <span class="w-2 h-2 rounded-full bg-current opacity-70"></span>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>

                            {{-- Kolom: Actions (detail, confirm, cancel) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3 items-center">

                                    {{-- View detail booking --}}
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                       class="text-[var(--tb-primary-soft)] hover:text-[var(--tb-primary)] transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Tombol konfirmasi & cancel hanya jika status masih pending --}}
                                    @if($booking->status === 'pending')
                                        {{-- Confirm booking --}}
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}"
                                              method="POST"
                                              class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-emerald-600 hover:text-emerald-700 transition-colors"
                                                    title="Confirm Booking">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        {{-- Cancel booking --}}
                                        <form action="{{ route('admin.bookings.cancel', $booking) }}"
                                              method="POST"
                                              class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-rose-600 hover:text-rose-700 transition-colors"
                                                    onclick="return confirm('Are you sure you want to cancel this booking?')"
                                                    title="Cancel Booking">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Placeholder extra actions --}}
                                    <button class="text-slate-400 hover:text-slate-600 transition-colors" title="More options">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Jika tidak ada data booking --}}
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                                    <h3 class="text-lg font-semibold text-slate-900 mb-1">No bookings found</h3>
                                    <p class="text-slate-500">
                                        There are no bookings matching your criteria.
                                    </p>
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
        @if($bookings->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between rounded-b-2xl">
                <div class="text-sm text-slate-600">
                    Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $bookings->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

{{-- =========================
     PAGE-SPECIFIC SCRIPTS
   ========================== --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const applyButton = document.querySelector('.btn-primary-modern');

        // Animasi loading kecil di tombol "Apply Filters"
        if (applyButton) {
            applyButton.addEventListener('click', function() {
                this.innerHTML = '<div class="spinner mr-2"></div> Applying...';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-filter"></i><span>Apply Filters</span>';
                }, 1000);
            });
        }
    });
</script>
@endpush
