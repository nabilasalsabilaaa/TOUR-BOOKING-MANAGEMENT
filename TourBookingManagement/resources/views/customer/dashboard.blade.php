@extends('layouts.app')

@section('title', 'Dashboard - TourBooking')

@section('content')
@php
    $user = Auth::user();
    $isAdmin = $user && $user->role === 'admin';

    // Route untuk tombol "Find Tours" / "Manage Tours"
    $toursRoute = $isAdmin
        ? route('admin.tours.index')
        : route('tours.index');
@endphp

<div class="container mx-auto px-4 py-8">
    {{-- HEADER --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[#010d4c]">
                        Welcome back, {{ $user->name }}!
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border border-gray-200 text-gray-600">
                        {{ $isAdmin ? 'Admin Dashboard' : 'Customer Dashboard' }}
                    </span>
                </div>
                <p class="text-gray-600 mt-2">
                    {{ $isAdmin ? 'Monitor tours and bookings in your system.' : "Here's your booking summary and quick overview" }}
                </p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <i class="fas fa-calendar-day text-primary"></i>
                <span>{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total bookings --}}
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-white text-sm font-medium">
                        {{ $isAdmin ? 'Total User Bookings' : 'Total Bookings' }}
                    </p>
                    <h3 class="text-2xl font-bold mt-1">{{ $totalBookings }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-bookmark text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-white text-sm flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    {{ $isAdmin ? 'All bookings in system' : 'All your bookings' }}
                </span>
            </div>
        </div>

        {{-- Confirmed --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Confirmed</p>
                    <h3 class="text-2xl font-bold text-success mt-1">{{ $confirmedBookings }}</h3>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">
                    {{ $isAdmin ? 'Confirmed customer bookings' : 'Ready to go' }}
                </span>
            </div>
        </div>

        {{-- Pending --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending</p>
                    <h3 class="text-2xl font-bold text-warning mt-1">{{ $pendingBookings }}</h3>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">
                    {{ $isAdmin ? 'Need your confirmation' : 'Awaiting confirmation' }}
                </span>
            </div>
        </div>

        {{-- Total spent --}}
        <div class="card-custom p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">
                        {{ $isAdmin ? 'Total Revenue' : 'Total Spent' }}
                    </p>
                    <h3 class="text-2xl font-bold text-primary mt-1">
                        Rp {{ number_format($totalSpent, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-wallet text-blue-500 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-gray-400 text-sm">
                    {{ $isAdmin ? 'From confirmed bookings' : 'All booking costs' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- RECENT BOOKINGS --}}
        <div class="lg:col-span-2">
            <div class="card-custom">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-[#010d4c]">
                        {{ $isAdmin ? 'Recent Customer Bookings' : 'Recent Bookings' }}
                    </h2>
                    <a href="{{ route('customer.bookings.index') }}"
                       class="text-primary hover:text-primary-dark text-sm font-medium flex items-center">
                        View All
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg transition-all duration-300 hover:bg-gray-100">
                                    <div class="flex items-center space-x-4">
                                        @if($booking->schedule->tour->thumbnail)
                                            <img src="{{ asset('storage/' . $booking->schedule->tour->thumbnail) }}"
                                                 alt="{{ $booking->schedule->tour->name }}"
                                                 class="h-12 w-12 rounded-lg object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-primary-light flex items-center justify-center">
                                                <i class="fas fa-map-marked-alt text-primary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $booking->schedule->tour->name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $booking->schedule->date->format('M d, Y') }} • {{ $booking->guests }} guests
                                            </p>
                                        </div>
                                    </div>
                                    <span class="status-badge status-{{ $booking->status }} px-3 py-1 text-xs font-medium">
                                        @if($booking->status === 'pending')
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        @elseif($booking->status === 'confirmed')
                                            <i class="fas fa-check-circle mr-1"></i>Confirmed
                                        @else
                                            <i class="fas fa-times-circle mr-1"></i>Cancelled
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-bookmark text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 mb-4">
                                {{ $isAdmin ? 'No recent bookings yet.' : 'No recent bookings' }}
                            </p>
                            <a href="{{ $toursRoute }}"
                               class="btn-primary-custom px-4 py-2 text-white rounded-lg inline-flex items-center">
                                <i class="fas fa-search mr-2"></i>
                                {{ $isAdmin ? 'Manage Tours' : 'Find Tours' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="space-y-6">
            {{-- QUICK ACTIONS --}}
            <div class="card-custom p-6">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-light mr-3">
                        <i class="fas fa-bolt text-primary text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-[#010d4c]">Quick Actions</h2>
                </div>
                <div class="space-y-3">
                    {{-- Find / Manage Tours --}}
                    <a href="{{ $toursRoute }}"
                       class="w-full btn-primary-custom py-3 text-center rounded-lg flex items-center justify-center transition">
                        <i class="fas fa-search mr-3"></i>
                        {{ $isAdmin ? 'Manage Tours' : 'Find Tours' }}
                    </a>

                    {{-- Booking history hanya make sense untuk customer, tapi kalau admin klik juga nggak bahaya --}}
                    <a href="{{ route('customer.bookings.index') }}"
                       class="w-full border border-gray-300 text-gray-700 py-3 text-center rounded-lg hover:bg-gray-50 flex items-center justify-center transition">
                        <i class="fas fa-history mr-3"></i>
                        {{ $isAdmin ? 'Customer Bookings' : 'Booking History' }}
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="w-full border border-gray-300 text-gray-700 py-3 text-center rounded-lg hover:bg-gray-50 flex items-center justify-center transition">
                        <i class="fas fa-user mr-3"></i>
                        Update Profile
                    </a>
                </div>
            </div>

            {{-- UPCOMING TOURS --}}
            @if($upcomingBookings->count() > 0)
                <div class="card-custom p-6">
                    <div class="flex items-center mb-6">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 mr-3">
                            <i class="fas fa-calendar-check text-green-500 text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-[#010d4c]">Upcoming Tours</h2>
                    </div>
                    <div class="space-y-4">
                        @foreach($upcomingBookings as $booking)
                            <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-green-800 text-sm">
                                            {{ $booking->schedule->tour->name }}
                                        </p>
                                        <p class="text-green-700 text-xs mt-1">
                                            <i class="far fa-calendar mr-1"></i>
                                            {{ $booking->schedule->date->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $booking->schedule->date->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- HELP CARD --}}
            <div class="card-custom p-6 bg-blue-50 border border-blue-100">
                <div class="flex items-center mb-3">
                    <i class="fas fa-question-circle text-blue-500 text-lg mr-2"></i>
                    <h3 class="text-lg font-semibold text-blue-800">Need Help?</h3>
                </div>
                <div class="space-y-2 text-sm text-blue-700">
                    <p>Our support team is here to help you with any questions about your bookings.</p>
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

    {{-- RECENT ACTIVITY --}}
    <div class="mt-8">
        <div class="card-custom p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-[#010d4c]">Recent Activity</h2>
                <span class="text-sm text-gray-500">Last 30 days</span>
            </div>
            <div class="space-y-4">
                @if($recentBookings->count() > 0)
                    @foreach($recentBookings->take(3) as $booking)
                        <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center">
                                    <i class="fas fa-bookmark text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    Booked "{{ $booking->schedule->tour->name }}"
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $booking->created_at->diffForHumans() }} •
                                    {{ $booking->guests }} guests •
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="status-badge status-{{ $booking->status }} text-xs">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-history text-gray-300 text-2xl mb-2"></i>
                        <p class="text-gray-500">No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        background:#010d4c;
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        width: fit-content;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #d97706;
    }

    .status-confirmed {
        background-color: #d1fae5;
        color: #059669;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #dc2626;
    }
</style>
@endpush
