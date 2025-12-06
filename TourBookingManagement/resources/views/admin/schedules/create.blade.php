@extends('layouts.app')

@section('title', 'Create Schedule')

@section('content')
<style>
    :root {
        --tb-primary: #010d4c;
        --tb-primary-soft: #4556a6;
        --tb-primary-dark: #2c396d;
        --tb-accent: #ffb524;
        --tb-accent-soft: #f8ca5b;
        --tb-bg-soft: #eef2ff;
    }

    .tour-option {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
    }
    
    .tour-name {
        font-weight: 500;
        color: #1f2937;
    }
    
    .tour-price {
        color: var(--tb-accent);
        font-weight: 600;
    }
    
    .tour-details {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 2px;
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
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-primary-custom:hover {
        filter: brightness(1.02);
        box-shadow: 0 15px 35px rgba(1, 13, 76, 0.3);
        transform: translateY(-1px);
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

    .bg-primary {
        background-color: var(--tb-primary);
    }

    .bg-primary-dark {
        background-color: var(--tb-primary-dark);
    }

    .checkbox-custom:checked {
        background-color: var(--tb-primary);
        border-color: var(--tb-primary);
    }

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
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('admin.schedules.index') }}" class="mr-4 text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create New Schedule</h1>
                        <p class="text-gray-600 mt-2">Add a new tour schedule to the system</p>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center transition-all duration-200">
                    <i class="fas fa-list mr-2"></i>
                    View All Schedules
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <!-- Form Card -->
        <div class="card-custom p-8">
            <!-- Form Header -->
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-light mr-4">
                    <i class="fas fa-calendar-plus text-primary text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Schedule Information</h2>
                    <p class="text-gray-600 text-sm">Fill in the details for the new tour schedule</p>
                </div>
            </div>

            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf

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
                                    <option value="{{ $tour->id }}" {{ old('tour_id') == $tour->id ? 'selected' : '' }}
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
                        <div id="tour-details" class="mt-3 p-4 bg-blue-50 rounded-lg hidden">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-600">Price:</span>
                                    <span id="tour-price" class="ml-2 font-semibold text-primary"></span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-600">Duration:</span>
                                    <span id="tour-duration" class="ml-2 font-semibold text-gray-800"></span>
                                </div>
                                <div class="col-span-2">
                                    <span class="font-medium text-gray-600">Location:</span>
                                    <span id="tour-location" class="ml-2 font-semibold text-gray-800"></span>
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
                                <input type="date" name="date" id="date" value="{{ old('date') }}" 
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
                                       value="{{ old('available_slots', 10) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                       required>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-user-plus"></i>
                                </div>
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
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-5 w-5 text-primary focus:ring-primary border-gray-300 rounded checkbox-custom">
                        </div>
                        <div class="ml-3">
                            <label for="is_active" class="text-sm font-medium text-gray-700">
                                Active Schedule
                            </label>
                            <p class="text-sm text-gray-500 mt-1">
                                When checked, this schedule will be visible to customers for booking.
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
                       class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center justify-center transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark focus:ring-2 focus:ring-primary focus:ring-offset-2 flex items-center justify-center transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Create Schedule
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
                        <p class="text-xs text-blue-600 mt-1">Choose from available tours. The price and details will be shown automatically.</p>
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
                        <p class="text-xs text-purple-600 mt-1">Set the maximum number of guests for this schedule.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
            
            tourDetails.classList.remove('hidden');
        } else {
            tourDetails.classList.add('hidden');
        }
    });
    
    // Trigger change event on page load if there's a selected tour
    document.addEventListener('DOMContentLoaded', function() {
        if (tourSelect.value) {
            tourSelect.dispatchEvent(new Event('change'));
        }
        
        // Add form submission loading state
        const form = document.querySelector('form');
        const submitButton = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function(e) {
            if (!form.classList.contains('prevent-multiple-submit')) {
                form.classList.add('prevent-multiple-submit');
                submitButton.disabled = true;
                submitButton.innerHTML = '<div class="spinner mr-2"></div> Creating...';
            }
        });
        
        // Validate date on input
        const dateInput = document.getElementById('date');
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                alert('Please select a future date.');
                this.value = '';
            }
        });
        
        // Validate slots input
        const slotsInput = document.getElementById('available_slots');
        slotsInput.addEventListener('input', function() {
            if (this.value < 1) {
                this.value = 1;
            }
        });
    });
</script>
@endpush
