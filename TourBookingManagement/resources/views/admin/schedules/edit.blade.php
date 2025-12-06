@extends('layouts.app')

@section('title', 'Edit Schedule - TourBooking Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('admin.schedules.index') }}" class="mr-4 text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Schedule</h1>
                        <p class="text-gray-600 mt-2">Update schedule information for #{{ $schedule->id }}</p>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center transition">
                    <i class="fas fa-list mr-2"></i>
                    View All Schedules
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <!-- Current Schedule Status -->
        <div class="card-custom p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-lg font-semibold text-gray-800">Current Schedule Status</h3>
                    <div class="flex items-center mt-2 space-x-4">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Status:</span>
                            <span class="status-badge {{ $schedule->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Booked Slots:</span>
                            <span class="font-medium text-gray-900">
                                {{ $schedule->bookings_count ?? 0 }} / {{ $schedule->available_slots }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    @if($schedule->is_active)
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full flex items-center">
                            <i class="fas fa-eye mr-1"></i> Visible to Customers
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full flex items-center">
                            <i class="fas fa-eye-slash mr-1"></i> Hidden from Customers
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card-custom p-8">
            <!-- Form Header -->
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-light mr-4">
                    <i class="fas fa-calendar-edit text-primary text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Schedule Information</h2>
                    <p class="text-gray-600 text-sm">Update the details for this tour schedule</p>
                </div>
            </div>

            <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST" class="prevent-multiple-submit">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Tour Selection -->
                    <div>
                        <label for="tour_id" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-route mr-2 text-primary"></i>
                            Select Tour *
                        </label>
                        <div class="relative">
                            <select name="tour_id" id="tour_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none appearance-none">
                                <option value="">Choose a tour...</option>
                                @foreach($tours as $tour)
                                    <option value="{{ $tour->id }}" 
                                            {{ old('tour_id', $schedule->tour_id) == $tour->id ? 'selected' : '' }}
                                            data-price="{{ $tour->price }}"
                                            data-duration="{{ $tour->duration_days }}"
                                            data-location="{{ $tour->location }}">
                                        {{ $tour->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        
                        <!-- Tour Details Preview -->
                        <div id="tour-details" class="mt-3 p-4 bg-blue-50 rounded-lg">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-600">Price:</span>
                                    <span id="tour-price" class="ml-2 font-semibold text-primary">
                                        @php
                                            $currentTour = $tours->firstWhere('id', $schedule->tour_id);
                                        @endphp
                                        @if($currentTour)
                                            Rp {{ number_format($currentTour->price, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-600">Duration:</span>
                                    <span id="tour-duration" class="ml-2 font-semibold text-gray-800">
                                        @if($currentTour)
                                            {{ $currentTour->duration_days }} days
                                        @endif
                                    </span>
                                </div>
                                <div class="col-span-2">
                                    <span class="font-medium text-gray-600">Location:</span>
                                    <span id="tour-location" class="ml-2 font-semibold text-gray-800">
                                        @if($currentTour)
                                            {{ $currentTour->location }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @error('tour_id')
                            <p class="mt-2 text-sm text-error flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Date and Slots Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="far fa-calendar mr-2 text-primary"></i>
                                Schedule Date *
                            </label>
                            <div class="relative">
                                <input type="date" name="date" id="date" 
                                       value="{{ old('date', $schedule->date->format('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                       required>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="far fa-calendar"></i>
                                </div>
                            </div>
                            @error('date')
                                <p class="mt-2 text-sm text-error flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Available Slots -->
                        <div>
                            <label for="available_slots" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-users mr-2 text-primary"></i>
                                Available Slots *
                            </label>
                            <div class="relative">
                                <input type="number" name="available_slots" id="available_slots" min="1" 
                                       value="{{ old('available_slots', $schedule->available_slots) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                       required>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                Currently booked: {{ $schedule->bookings_count ?? 0 }} slots
                            </div>
                            @error('available_slots')
                                <p class="mt-2 text-sm text-error flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-start pt-4 border-t border-gray-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}
                                   class="h-5 w-5 text-primary focus:ring-primary border-gray-300 rounded checkbox-custom">
                        </div>
                        <div class="ml-3">
                            <label for="is_active" class="text-sm font-medium text-gray-700">
                                Active Schedule
                            </label>
                            <p class="text-sm text-gray-500 mt-1">
                                When checked, this schedule will be visible to customers for booking.
                                @if($schedule->bookings_count > 0)
                                    <span class="text-warning font-medium">Note: There are active bookings for this schedule.</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @error('is_active')
                        <p class="mt-2 text-sm text-error flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('admin.schedules.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center justify-center transition">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="btn-primary-custom px-6 py-3 text-white rounded-lg text-sm font-medium flex items-center justify-center transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Update Schedule
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Information -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800">Tour Selection</h4>
                        <p class="text-xs text-blue-600 mt-1">Choose from available tours. Changing the tour will affect all future bookings.</p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-calendar-check text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-green-800">Date Selection</h4>
                        <p class="text-xs text-green-600 mt-1">Select a future date for the tour. Past dates are not allowed.</p>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-users text-purple-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-purple-800">Available Slots</h4>
                        <p class="text-xs text-purple-600 mt-1">Cannot be lower than currently booked slots ({{ $schedule->bookings_count ?? 0 }}).</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @if($schedule->bookings_count == 0)
        <div class="card-custom p-6 mt-6 border border-red-200">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-red-50 mr-3">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-red-800">Danger Zone</h3>
                    <p class="text-red-600 text-sm">Irreversible actions</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-3 sm:mb-0">
                    <h4 class="font-medium text-gray-900">Delete this schedule</h4>
                    <p class="text-sm text-gray-600">Once deleted, this schedule cannot be recovered.</p>
                </div>
                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-error text-white rounded-lg text-sm font-medium hover:bg-red-600 flex items-center transition"
                            onclick="return confirm('Are you sure you want to delete this schedule? This action cannot be undone.')">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Schedule
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card-custom p-6 mt-6 border border-yellow-200">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-50 mr-3">
                    <i class="fas fa-info-circle text-yellow-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800">Schedule Has Bookings</h3>
                    <p class="text-yellow-700 text-sm">
                        This schedule has {{ $schedule->bookings_count }} active booking(s). 
                        It cannot be deleted until all bookings are cancelled or completed.
                    </p>
                </div>
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

    .checkbox-custom:checked {
        background-color: var(--tb-primary);
        border-color: var(--tb-primary);
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
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

    .card-custom {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--tb-primary) 0%, var(--tb-primary-dark) 100%);
        color: #ffffff;
    }

    .text-error {
        color: #b91c1c;
    }

    .bg-primary-light {
        background: var(--tb-bg-soft);
    }

    .text-primary {
        color: var(--tb-primary-soft);
    }
</style>
@endpush

@push('scripts')
<script>
    // Set minimum date to today
    document.getElementById('date').min = new Date().toISOString().split('T')[0];
    
    // Tour details preview
    const tourSelect = document.getElementById('tour_id');
    const tourDetails = document.getElementById('tour-details');
    const tourPrice = document.getElementById('tour-price');
    const tourDuration = document.getElementById('tour-duration');
    const tourLocation = document.getElementById('tour-location');
    
    tourSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const price = selectedOption.getAttribute('data-price');
            const duration = selectedOption.getAttribute('data-duration');
            const location = selectedOption.getAttribute('data-location');
            
            if (price) {
                tourPrice.textContent = 'Rp ' + Number(price).toLocaleString('id-ID');
            }
            
            if (duration) {
                tourDuration.textContent = duration + ' days';
            }
            
            if (location) {
                tourLocation.textContent = location;
            }
        }
    });

    // Validate available slots
    const availableSlotsInput = document.getElementById('available_slots');
    const currentBookings = {{ $schedule->bookings_count ?? 0 }};
    
    availableSlotsInput.addEventListener('change', function() {
        const newValue = parseInt(this.value);
        if (newValue < currentBookings) {
            alert('Available slots cannot be less than current bookings (' + currentBookings + ').');
            this.value = currentBookings;
        }
    });
</script>
@endpush
