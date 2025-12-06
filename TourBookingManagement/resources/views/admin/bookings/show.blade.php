@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')

{{-- =========================
     CUSTOM STYLES (PAGE ONLY)
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

    /* Kartu umum untuk section konten */
    .card-custom {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 1.5rem;
    }

    /* Item info berbaris (label + value) */
    .info-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    /* Header section (ikon + judul) */
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 14px;
        margin-right: 12px;
    }

    .section-icon-primary {
        background: var(--tb-bg-soft);
        color: var(--tb-primary-soft);
    }

    /* Timeline gaya vertical */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 999px;
        background: #fff;
        border: 2px solid var(--tb-accent-soft);
        box-shadow: 0 0 0 3px rgba(255, 181, 36, 0.35);
    }

    .timeline-item.completed::before {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.35);
    }

    .timeline-item.current::before {
        border-color: var(--tb-primary-soft);
        box-shadow: 0 0 0 3px rgba(69, 86, 166, 0.35);
    }

    /* Badge status di header */
    .status-pill {
        padding: 0.35rem 0.9rem;
        font-size: 0.75rem;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.6);
        background: var(--tb-bg-soft);
        color: var(--tb-primary);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Tombol utama (Print) */
    .btn-primary-modern {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--tb-primary) 0%, var(--tb-primary-dark) 100%);
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 14px 30px rgba(1, 13, 76, 0.35);
    }

    .btn-primary-modern:hover {
        box-shadow: 0 20px 45px rgba(1, 13, 76, 0.45);
        transform: translateY(-1px);
    }

    /* Tombol accent (Confirm) */
    .btn-accent-modern {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--tb-accent) 0%, var(--tb-accent-soft) 100%);
        color: #010d4c;
        font-weight: 600;
        box-shadow: 0 14px 30px rgba(248, 202, 91, 0.45);
    }

    .btn-accent-modern:hover {
        filter: brightness(0.97);
    }

    /* Spinner loading kecil untuk tombol */
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
         HEADER SECTION
       ========================== --}}
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center">
                    {{-- Back icon kecil ke index bookings --}}
                    <a href="{{ route('admin.bookings.index') }}"
                       class="mr-4 text-slate-500 hover:text-slate-700 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Booking Details</h1>
                        <div class="flex items-center mt-2 gap-2">
                            <p class="text-slate-500">Booking #{{ $booking->id }}</p>
                            <span class="mx-1 text-slate-300">•</span>
                            <span class="status-pill">
                                <span class="w-2 h-2 rounded-full bg-current opacity-70"></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Header actions: confirm/cancel/back/print --}}
            <div class="flex flex-wrap gap-3">
                @if($booking->status === 'pending')
                    {{-- Confirm booking (Admin) --}}
                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="btn-accent-modern px-4 py-2 text-sm flex items-center gap-2 transition-all duration-200">
                            <i class="fas fa-check"></i> Confirm Booking
                        </button>
                    </form>

                    {{-- Cancel booking (Admin) --}}
                    <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-full bg-rose-500 text-white text-sm font-semibold hover:bg-rose-600 flex items-center gap-2 transition-all duration-200 shadow-md hover:shadow-lg"
                                onclick="return confirm('Are you sure you want to cancel this booking?')">
                            <i class="fas fa-times"></i> Cancel Booking
                        </button>
                    </form>
                @endif

                {{-- Back to list --}}
                <a href="{{ route('admin.bookings.index') }}"
                   class="px-4 py-2 rounded-full bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200 flex items-center gap-2 transition-all duration-200">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>

                {{-- Print current page --}}
                <button
                    class="btn-primary-modern px-4 py-2 text-sm flex items-center gap-2 transition-all duration-200"
                    onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        {{-- =========================
             LEFT: MAIN INFO & TOUR
           ========================== --}}
        <div class="xl:col-span-2 space-y-8">

            {{-- Booking Timeline --}}
            <div class="card-custom">
                <div class="section-header">
                    <div class="section-icon section-icon-primary">
                        <i class="fas fa-stream"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Booking Timeline</h2>
                </div>

                <div class="timeline">
                    {{-- Booking created --}}
                    <div class="timeline-item completed">
                        <div class="text-sm font-semibold text-slate-900">Booking Created</div>
                        <div class="text-xs text-slate-500 mt-1">
                            {{ $booking->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>

                    {{-- Booking confirmed / awaiting --}}
                    @if($booking->status === 'confirmed' || $booking->status === 'completed')
                        <div class="timeline-item completed">
                            <div class="text-sm font-semibold text-slate-900">Booking Confirmed</div>
                            <div class="text-xs text-slate-500 mt-1">
                                @if($booking->updated_at)
                                    {{ $booking->updated_at->format('M d, Y H:i') }}
                                @else
                                    Date not available
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="timeline-item current">
                            <div class="text-sm font-semibold text-slate-900">Awaiting Confirmation</div>
                            <div class="text-xs text-slate-500 mt-1">Pending admin approval</div>
                        </div>
                    @endif

                    {{-- Completed / upcoming tour --}}
                    @if($booking->status === 'completed')
                        <div class="timeline-item completed">
                            <div class="text-sm font-semibold text-slate-900">Tour Completed</div>
                            <div class="text-xs text-slate-500 mt-1">
                                {{ $booking->schedule->date->format('M d, Y') }}
                            </div>
                        </div>
                    @elseif($booking->status === 'confirmed')
                        <div class="timeline-item current">
                            <div class="text-sm font-semibold text-slate-900">Upcoming Tour</div>
                            <div class="text-xs text-slate-500 mt-1">
                                Scheduled for {{ $booking->schedule->date->format('M d, Y') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tour Information --}}
            <div class="card-custom">
                <div class="section-header">
                    <div class="section-icon bg-emerald-50 text-emerald-600">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Tour Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tour name --}}
                    <div class="info-item">
                        <div class="w-10 h-10 rounded-xl bg-[var(--tb-bg-soft)] flex items-center justify-center mr-4">
                            <i class="fas fa-route text-[var(--tb-primary-soft)]"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Tour Name</p>
                            <p class="text-base font-semibold text-slate-900 mt-0.5">
                                {{ $booking->schedule->tour->name }}
                            </p>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="info-item">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mr-4">
                            <i class="fas fa-map-marker-alt text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Location</p>
                            <p class="text-base font-semibold text-slate-900 mt-0.5">
                                {{ $booking->schedule->tour->location }}
                            </p>
                        </div>
                    </div>

                    {{-- Schedule date --}}
                    <div class="info-item">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mr-4">
                            <i class="far fa-calendar text-amber-500"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Schedule Date</p>
                            <p class="text-base font-semibold text-slate-900 mt-0.5">
                                {{ $booking->schedule->date->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Duration --}}
                    <div class="info-item">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mr-4">
                            <i class="far fa-clock text-indigo-500"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Duration</p>
                            <p class="text-base font-semibold text-slate-900 mt-0.5">
                                {{ $booking->schedule->tour->duration_days }} days
                            </p>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2 info-item">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center mr-4">
                            <i class="fas fa-info-circle text-slate-500"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Description</p>
                            <p class="text-base text-slate-900 mt-1">
                                {{ $booking->schedule->tour->description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================
             RIGHT: SUMMARY & CUSTOMER
           ========================== --}}
        <div class="space-y-8">

            {{-- Booking Summary --}}
            <div class="card-custom">
                <div class="section-header">
                    <div class="section-icon bg-[var(--tb-bg-soft)] text-[var(--tb-primary-soft)]">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Booking Summary</h2>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-500">Booking ID</span>
                        <span class="font-semibold text-slate-900">#{{ $booking->id }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-500">Booking Date</span>
                        <span class="font-semibold text-slate-900">
                            {{ $booking->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-500">Number of Guests</span>
                        <span class="font-semibold text-slate-900">
                            {{ $booking->guests }} guests
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-500">Price per Person</span>
                        <span class="font-semibold text-slate-900">
                            Rp {{ number_format($booking->total_price / $booking->guests, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Total amount --}}
                    <div class="border-t border-slate-200 pt-4 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-slate-800">Total Amount</span>
                            <span class="text-xl font-extrabold text-[var(--tb-primary)]">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="card-custom">
                <div class="section-header">
                    <div class="section-icon bg-purple-50 text-purple-500">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Customer Information</h2>
                </div>

                <div class="space-y-4">
                    {{-- Nama customer --}}
                    <div class="flex items-center py-2">
                        <div class="w-10 h-10 rounded-full bg-[var(--tb-bg-soft)] flex items-center justify-center mr-3">
                            <i class="fas fa-user text-[var(--tb-primary-soft)]"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $booking->customer_name }}</p>
                            <p class="text-xs text-slate-500">Customer</p>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="flex items-center py-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3">
                            <i class="fas fa-phone text-blue-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">{{ $booking->customer_phone }}</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-center py-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center mr-3">
                            <i class="fas fa-envelope text-emerald-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">{{ $booking->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions (placeholder, bisa dihubungkan ke fitur lain nanti) --}}
            <div class="card-custom">
                <div class="section-header">
                    <div class="section-icon bg-orange-50 text-orange-500">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Quick Actions</h2>
                </div>
                <div class="space-y-3">
                    <button
                        class="w-full flex items-center justify-center px-4 py-3 bg-slate-50 border border-slate-200 rounded-full text-slate-700 hover:bg-slate-100 transition-all duration-200">
                        <i class="fas fa-envelope mr-2"></i>
                        Send Reminder
                    </button>
                    <button
                        class="w-full flex items-center justify-center px-4 py-3 bg-slate-50 border border-slate-200 rounded-full text-slate-700 hover:bg-slate-100 transition-all duration-200">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Generate Invoice
                    </button>
                    <button
                        class="w-full flex items-center justify-center px-4 py-3 bg-slate-50 border border-slate-200 rounded-full text-slate-700 hover:bg-slate-100 transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Booking
                    </button>
                </div>
            </div>

            {{-- Customer Notes (opsional) --}}
            @if($booking->notes)
                <div class="card-custom">
                    <div class="section-header">
                        <div class="section-icon bg-amber-50 text-amber-500">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800">Customer Notes</h2>
                    </div>
                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-sm text-amber-900">
                        {{ $booking->notes }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

{{-- =========================
     PAGE-SPECIFIC SCRIPTS
   ========================== --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan efek "Processing..." sementara pada tombol quick actions
        const actionButtons = document.querySelectorAll('.card-custom button');

        actionButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Hindari double klik yang berulang-ulang
                if (this.classList.contains('prevent-multiple-submit')) return;

                const originalText = this.innerHTML;
                this.innerHTML = '<div class="spinner mr-2"></div> Processing...';
                this.classList.add('prevent-multiple-submit');

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('prevent-multiple-submit');
                }, 2000);
            });
        });
    });
</script>
@endpush
