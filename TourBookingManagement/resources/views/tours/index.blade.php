@extends('layouts.app')

@section('title', 'Find Tours - TourBooking')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- HEADER --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#010d4c]">
                    Discover Your Next Tour
                </h1>
                <p class="text-gray-600 mt-2">
                    Browse our available tours and book your next adventure.
                </p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i class="fas fa-info-circle text-primary"></i>
                <span>Showing {{ $tours->total() }} tour(s)</span>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <form action="{{ route('tours.index') }}" method="GET" class="card-custom mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-[#010d4c]">
                Filters
            </h2>
            @if(request('search') || request('sort'))
                <a href="{{ route('tours.index') }}"
                   class="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1">
                    <i class="fas fa-times text-[10px]"></i>
                    Clear filters
                </a>
            @endif
        </div>
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            {{-- Search --}}
            <div>
                <label for="search" class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-xs"></i>
                    </div>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name or location..."
                        class="pl-9 pr-3 py-2 w-full border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                </div>
            </div>

            {{-- Sort --}}
            <div>
                <label for="sort" class="block text-xs font-semibold text-gray-600 mb-1">Sort by</label>
                <select
                    id="sort"
                    name="sort"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary"
                >
                    <option value="">Default (Newest)</option>
                    <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </div>

            {{-- Submit --}}
            <div class="flex md:justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg btn-primary-custom text-sm">
                    <i class="fas fa-filter text-xs"></i>
                    Apply Filters
                </button>
            </div>
        </div>
    </form>

    {{-- TOUR GRID --}}
    @if($tours->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($tours as $tour)
                <div class="card-custom overflow-hidden flex flex-col h-full">
                    {{-- Thumbnail --}}
                    @if($tour->thumbnail)
                        <img src="{{ asset('storage/' . $tour->thumbnail) }}"
                             alt="{{ $tour->name }}"
                             class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-indigo-50 flex items-center justify-center">
                            <i class="fas fa-map-marked-alt text-3xl text-indigo-300"></i>
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">
                                {{ $tour->name }}
                            </h3>
                            @if($tour->is_active)
                                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[11px] font-semibold">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-[11px] font-semibold">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-gray-500 flex items-center mb-3">
                            <i class="fas fa-map-marker-alt text-primary text-xs mr-1.5"></i>
                            <span class="line-clamp-1">
                                {{ $tour->location ?? 'Location not specified' }}
                            </span>
                        </p>

                        <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                            <div>
                                <p class="text-xs text-gray-400 uppercase mb-0.5">Price</p>
                                <p class="font-semibold text-gray-900">
                                    Rp {{ number_format($tour->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase mb-0.5">Duration</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $tour->duration_days ?? '-' }} days
                                </p>
                            </div>
                            @if(isset($tour->schedules_count))
                                <div class="col-span-2 mt-1">
                                    <p class="text-xs text-gray-400 uppercase mb-0.5">Schedules</p>
                                    <p class="text-xs text-gray-600 flex items-center">
                                        <i class="far fa-calendar-alt text-[11px] mr-1.5 text-primary"></i>
                                        {{ $tour->schedules_count }} active schedule(s)
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="mt-auto pt-2 flex items-center justify-between gap-2">
                            <a href="{{ route('tours.show', $tour) }}"
                               class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary-dark">
                                View Details
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>

                            @if($tour->is_active)
                                <a href="{{ route('tours.show', $tour) }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg btn-primary-custom text-xs font-semibold">
                                    <i class="fas fa-ticket-alt text-[11px]"></i>
                                    Book Tour
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="flex items-center justify-between text-sm text-gray-600">
            <div>
                Showing
                <span class="font-semibold">{{ $tours->firstItem() }}</span>
                to
                <span class="font-semibold">{{ $tours->lastItem() }}</span>
                of
                <span class="font-semibold">{{ $tours->total() }}</span>
                results
            </div>
            <div class="flex">
                {{ $tours->links() }}
            </div>
        </div>
    @else
        {{-- EMPTY STATE --}}
        <div class="card-custom py-12 flex flex-col items-center justify-center">
            <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="fas fa-search text-gray-400 text-2xl"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">No tours found</h2>
            <p class="text-gray-500 text-sm mb-4">
                Try adjusting your search or filters to find what you're looking for.
            </p>
            <a href="{{ route('tours.index') }}"
               class="btn-primary-custom px-4 py-2 text-white rounded-lg inline-flex items-center text-sm">
                <i class="fas fa-undo mr-2 text-xs"></i>
                Reset Search
            </a>
        </div>
    @endif
</div>
@endsection
