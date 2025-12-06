@extends('layouts.app')

@section('title', 'Manage Schedules - TourBooking Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- =========================
         HEADER SECTION
       ========================== --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-900">Manage Schedules</h1>
                <p class="text-gray-600 mt-2">
                    Create and manage tour schedules efficiently
                </p>

                {{-- Info tambahan jika sedang filter berdasarkan tour tertentu --}}
                @if(!empty($tourId))
                    @php
                        $currentTour = $tours->firstWhere('id', $tourId);
                    @endphp
                    @if($currentTour)
                        <div class="mt-2 text-sm text-gray-600">
                            Showing schedules for:
                            <span class="font-semibold">{{ $currentTour->name }}</span>
                            <a href="{{ route('admin.schedules.index') }}"
                               class="ml-2 text-xs text-primary hover:underline">
                                Clear filter
                            </a>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Tombol untuk membuat jadwal baru --}}
            <div class="flex space-x-3">
                <a href="{{ route('admin.schedules.create') }}" 
                   class="btn-primary-custom px-4 py-2 text-white rounded-lg hover:shadow-lg flex items-center transition">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Schedule
                </a>
            </div>
        </div>
    </div>

    {{-- =========================
         STATISTICS CARDS
         (Ringkasan jadwal secara global)
       ========================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Schedules --}}
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-white text-sm font-medium">Total Schedules</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $allSchedules->count() }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-white text-sm flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    All time schedules
                </span>
            </div>
        </div>
        
        {{-- Active Schedules (jadwal yang masih aktif & ditampilkan ke user) --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Schedules</p>
                    <h3 class="text-2xl font-bold text-success mt-1">
                        {{ $allSchedules->where('is_active', true)->count() }}
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
        
        {{-- Upcoming (jadwal dengan tanggal >= hari ini) --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Upcoming</p>
                    <h3 class="text-2xl font-bold text-info mt-1">
                        {{ $allSchedules->where('date', '>=', now())->count() }}
                    </h3>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-clock text-blue-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">Future schedules</span>
            </div>
        </div>
        
        {{-- Full Bookings (jadwal yang slotnya sudah habis) --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Full Bookings</p>
                    <h3 class="text-2xl font-bold text-warning mt-1">
                        {{ $allSchedules->where('available_slots', '<=', 0)->count() }}
                    </h3>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <i class="fas fa-users text-yellow-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">No available slots</span>
            </div>
        </div>
    </div>

    {{-- =========================
         SCHEDULES TABLE
       ========================== --}}
    <div class="card-custom">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tour Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date & Time
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Capacity
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bookings
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($schedules as $schedule)
                    <tr class="table-row-hover">
                        {{-- =========================
                             Kolom: Tour details
                           ========================== --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-primary-light rounded-lg flex items-center justify-center">
                                    <i class="fas fa-route text-primary text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->tour->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 flex items-center mt-1">
                                        <i class="fas fa-map-marker-alt mr-1 text-primary text-xs"></i>
                                        {{ $schedule->tour->location }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $schedule->tour->duration_days }} days
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- =========================
                             Kolom: Date & Time
                           ========================== --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $schedule->date->format('M d, Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $schedule->date->diffForHumans() }}
                            </div>
                            @if($schedule->date < now())
                                <div class="text-xs text-error mt-1 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Past schedule
                                </div>
                            @endif
                        </td>

                        {{-- =========================
                             Kolom: Capacity (progress bar & angka)
                           ========================== --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                // Hitung total slot:
                                // - Jika tour punya kapasitas global, pakai itu
                                // - Jika tidak, estimasi dari slot tersisa + jumlah booking
                                $totalSlots = $schedule->tour->capacity ?? ($schedule->available_slots + $schedule->bookings_count);
                                $totalSlots = max($totalSlots, 0);
                                $booked = $schedule->bookings_count;
                                $bookedPercentage = $totalSlots > 0 ? ($booked / $totalSlots) * 100 : 0;
                            @endphp
                            <div class="flex items-center">
                                {{-- Progress bar persen terisi --}}
                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-3 overflow-hidden">
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $bookedPercentage }}%"></div>
                                </div>
                                <div class="text-sm">
                                    <span class="font-medium {{ $schedule->available_slots > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $schedule->available_slots }}
                                    </span>
                                    <span class="text-gray-500">/{{ $totalSlots }}</span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ number_format($bookedPercentage, 0) }}% booked
                            </div>
                        </td>

                        {{-- =========================
                             Kolom: Bookings (jumlah & total revenue)
                           ========================== --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-primary"></i>
                                {{ $schedule->bookings_count }} bookings
                            </div>
                            @if($schedule->bookings_count > 0)
                            <div class="text-xs text-gray-500 mt-1">
                                Rp {{ number_format($schedule->bookings->sum('total_price'), 0, ',', '.') }} revenue
                            </div>
                            @endif
                        </td>

                        {{-- =========================
                             Kolom: Status (active/inactive + label tambahan)
                           ========================== --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                @if($schedule->is_active)
                                    <span class="badge status-active">
                                        <i class="fas fa-eye mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge status-inactive">
                                        <i class="fas fa-eye-slash mr-1"></i> Inactive
                                    </span>
                                @endif
                                
                                @if($schedule->date < now())
                                    <span class="badge status-past">
                                        <i class="fas fa-history mr-1"></i> Past
                                    </span>
                                @elseif($schedule->available_slots <= 0)
                                    <span class="badge status-full">
                                        <i class="fas fa-times mr-1"></i> Full
                                    </span>
                                @elseif($schedule->date > now())
                                    <span class="badge status-upcoming">
                                        <i class="fas fa-clock mr-1"></i> Upcoming
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- =========================
                             Kolom: Actions (edit, lihat bookings, delete)
                           ========================== --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                {{-- Edit jadwal --}}
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" 
                                   class="text-primary hover:text-primary-dark transition-colors"
                                   title="Edit Schedule">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Lihat daftar bookings milik jadwal ini (filter di halaman bookings) --}}
                                <a href="{{ route('admin.bookings.index') }}?schedule={{ $schedule->id }}" 
                                   class="text-info hover:text-blue-800 transition-colors"
                                   title="View Bookings">
                                    <i class="fas fa-list"></i>
                                </a>

                                {{-- Hapus jadwal (perlu hati-hati, bisa konsekuen ke bookings) --}}
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-error hover:text-red-800 transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this schedule? This will also delete all related bookings.')"
                                            title="Delete Schedule">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    {{-- =========================
                         State kosong: belum ada schedule
                       ========================== --}}
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No schedules found</h3>
                                <p class="text-gray-500 mb-4">Get started by creating your first schedule.</p>
                                <a href="{{ route('admin.schedules.create') }}" 
                                   class="btn-primary-custom px-4 py-2 text-white rounded-lg flex items-center transition">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create New Schedule
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
        @if($schedules->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $schedules->firstItem() }} to {{ $schedules->lastItem() }} of {{ $schedules->total() }} results
            </div>
            <div class="flex space-x-2">
                {{ $schedules->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

{{-- =========================
     PAGE-SPECIFIC STYLES
     (Dipush ke stack "styles")
   ========================== --}}
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
    
    /* Badge status di kolom status */
    .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .status-active {
        background-color: #d1fae5;
        color: #059669;
    }
    
    .status-inactive {
        background-color: #fef3c7;
        color: #d97706;
    }
    
    .status-full {
        background-color: #fee2e2;
        color: #dc2626;
    }
    
    .status-upcoming {
        background-color: #dbeafe;
        color: #2563eb;
    }

    .status-past {
        background-color: #e5e7eb;
        color: #374151;
    }

    /* Card wrapper utama */
    .card-custom {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    /* Tombol primary gradasi */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--tb-primary) 0%, var(--tb-primary-dark) 100%);
        color: #ffffff;
    }

    /* Hover effect untuk row tabel */
    .table-row-hover {
        transition: all 0.2s ease;
    }

    .table-row-hover:hover {
        background-color: var(--tb-bg-soft);
        transform: translateY(-1px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }

    .bg-primary {
        background-color: var(--tb-primary);
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

{{-- =========================
     PAGE-SPECIFIC SCRIPTS
     (Animasi kecil progress bar)
   ========================== --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua progress bar (div .bg-primary di capacity)
        const progressBars = document.querySelectorAll('.bg-primary');

        progressBars.forEach(bar => {
            const width = bar.style.width; // width awal (persentase booking)
            bar.style.transition = 'width 0.5s ease-in-out';
            setTimeout(() => {
                bar.style.width = width; // trigger animasi lebar
            }, 100);
        });
    });
</script>
@endpush
