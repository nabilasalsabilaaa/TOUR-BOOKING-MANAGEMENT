@extends('layouts.app')

@section('title', 'My Bookings - TourBooking')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-[#010d4c]">My Bookings</h1>
        <p class="text-gray-600 mt-1">View and manage all your bookings.</p>
    </div>

    {{-- STATISTICS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        
        {{-- Total Bookings --}}
        <div class="p-6 rounded-xl shadow bg-[#010d4c] text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">Total Bookings</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $bookings->total() }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-bookmark text-xl"></i>
                </div>
            </div>
            <p class="mt-4 text-sm opacity-80">All bookings made on your account.</p>
        </div>

        {{-- Confirmed --}}
        <div class="p-6 rounded-xl shadow bg-white border">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Confirmed</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-1">
                        {{ $bookings->where('status','confirmed')->count() }}
                    </h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-500">Tours that are fully confirmed.</p>
        </div>

        {{-- Pending --}}
        <div class="p-6 rounded-xl shadow bg-white border">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Pending</p>
                    <h3 class="text-3xl font-bold text-yellow-500 mt-1">
                        {{ $bookings->where('status','pending')->count() }}
                    </h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-500">Awaiting approval.</p>
        </div>

        {{-- Total Spent --}}
        <div class="p-6 rounded-xl shadow bg-white border">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Total Spent</p>
                    <h3 class="text-3xl font-bold text-[#4556a6] mt-1">
                        Rp {{ number_format($bookings->sum('total_price'),0,',','.') }}
                    </h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-wallet text-[#4556a6] text-xl"></i>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-500">Total amount spent on tours.</p>
        </div>
    </div>

    {{-- BOOKING LIST --}}
    @if($bookings->count() > 0)

    <div class="space-y-6">
        @foreach($bookings as $booking)
        <div class="p-6 rounded-xl shadow bg-white border hover:shadow-lg transition">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                {{-- IMAGE + BASIC INFO --}}
                <div class="flex items-start gap-4 flex-1">

                    {{-- Thumbnail --}}
                    @if($booking->schedule->tour->thumbnail)
                        <img src="{{ asset('storage/'.$booking->schedule->tour->thumbnail) }}"
                            class="w-20 h-20 rounded-lg object-cover shadow">
                    @else
                        <div class="w-20 h-20 rounded-lg bg-[#eef2ff] flex items-center justify-center">
                            <i class="fas fa-map text-2xl text-[#4556a6]"></i>
                        </div>
                    @endif

                    {{-- Info --}}
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-[#010d4c]">
                            {{ $booking->schedule->tour->name }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600 mt-3">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day mr-2 text-[#4556a6]"></i>
                                {{ $booking->schedule->date->format('M d, Y') }}
                            </div>

                            <div class="flex items-center">
                                <i class="fas fa-users mr-2 text-[#4556a6]"></i>
                                {{ $booking->guests }} guests
                            </div>

                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave mr-2 text-[#4556a6]"></i>
                                <span class="font-semibold">
                                    Rp {{ number_format($booking->total_price,0,',','.') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <i class="fas fa-map-marker-alt mr-2 text-[#4556a6]"></i>
                            {{ $booking->schedule->tour->location }}
                        </div>

                    </div>
                </div>

                {{-- STATUS + ACTIONS --}}
                <div class="flex flex-col items-start lg:items-end gap-3">

                    {{-- Status --}}
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($booking->status === 'pending')
                            bg-yellow-100 text-yellow-700
                        @elseif($booking->status === 'confirmed')
                            bg-green-100 text-green-700
                        @else
                            bg-red-100 text-red-700
                        @endif
                    ">
                        @if($booking->status === 'pending')
                            <i class="fas fa-clock mr-1"></i> Pending
                        @elseif($booking->status === 'confirmed')
                            <i class="fas fa-check-circle mr-1"></i> Confirmed
                        @else
                            <i class="fas fa-times-circle mr-1"></i> Cancelled
                        @endif
                    </span>

                    <div class="text-xs text-gray-500">
                        Booked on {{ $booking->created_at->format('M d, Y') }}
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-2">
                        <a href="{{ route('customer.bookings.show', $booking) }}"
                            class="bg-[#010d4c] text-white px-4 py-2 rounded-lg text-sm shadow">
                            <i class="fas fa-eye mr-1"></i>Details
                        </a>

                        @if($booking->status === 'pending')
                        <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button 
                                onclick="return confirm('Are you sure you want to cancel this booking?')"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm shadow">
                                <i class="fas fa-times mr-1"></i>Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

            </div>

            {{-- NOTES --}}
            @if($booking->notes)
            <div class="mt-4 p-4 bg-[#eef2ff] border border-[#d7ddff] rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-sticky-note text-[#4556a6] mr-3 mt-1"></i>
                    <div>
                        <p class="font-medium text-[#4556a6] text-sm">Special Requests</p>
                        <p class="text-sm text-[#2c396d] mt-1">{{ $booking->notes }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- UPCOMING ALERT --}}
            @if($booking->status === 'confirmed' && $booking->schedule->date->isFuture())
            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-green-500 mr-2"></i>
                    <span class="text-sm text-green-700 font-medium">
                        Your tour is coming up: {{ $booking->schedule->date->format('F j, Y') }}
                    </span>
                </div>
            </div>
            @endif

        </div>
        @endforeach
    </div>

    {{-- PAGINATION --}}
    <div class="mt-8 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings
        </div>
        <div>
            {{ $bookings->links() }}
        </div>
    </div>

    @else

    {{-- EMPTY STATE --}}
    <div class="p-12 border bg-white rounded-xl shadow text-center">
        <div class="w-20 h-20 mx-auto bg-[#eef2ff] rounded-full flex items-center justify-center mb-6">
            <i class="fas fa-bookmark text-[#4556a6] text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-[#010d4c] mb-2">No Bookings Yet</h2>
        <p class="text-gray-600 mb-6">Start exploring our tours and make your first booking!</p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('tours.index') }}" class="bg-[#010d4c] text-white px-6 py-3 rounded-lg shadow">
                <i class="fas fa-search mr-2"></i>Browse Tours
            </a>
            <a href="{{ route('home') }}" class="px-6 py-3 border rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-home mr-2"></i>Back to Home
            </a>
        </div>
    </div>

    @endif

</div>
@endsection
