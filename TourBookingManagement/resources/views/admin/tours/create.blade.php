@extends('layouts.app')

@section('title', (isset($tour) ? 'Edit Tour' : 'Create Tour') . ' - TourBooking Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('admin.tours.index') }}" class="mr-4 text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ isset($tour) ? 'Edit Tour' : 'Create New Tour' }}
                        </h1>
                        <p class="text-gray-600 mt-2">
                            {{ isset($tour) ? 'Update tour information' : 'Add a new tour package to your system' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.tours.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center transition">
                    <i class="fas fa-list mr-2"></i>
                    View All Tours
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Form Card -->
        <div class="card-custom p-8">
            <!-- Form Header -->
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-light mr-4">
                    <i class="fas fa-{{ isset($tour) ? 'edit' : 'plus' }} text-primary text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Tour Information</h2>
                    <p class="text-gray-600 text-sm">Fill in the details for your tour package</p>
                </div>
            </div>

            <form action="{{ isset($tour) ? route('admin.tours.update', $tour) : route('admin.tours.store') }}" method="POST" enctype="multipart/form-data" class="prevent-multiple-submit">
                @csrf
                @if(isset($tour))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Main Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Tour Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-route mr-2 text-primary"></i>
                                Tour Name *
                            </label>
                            <div class="relative">
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $tour->name ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                       placeholder="Enter tour name"
                                       required>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-signature"></i>
                                </div>
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-error flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-align-left mr-2 text-primary"></i>
                                Description
                            </label>
                            <textarea name="description" id="description" rows="5"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none resize-none"
                                      placeholder="Describe the tour experience, highlights, and what customers can expect">{{ old('description', $tour->description ?? '') }}</textarea>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">Provide a compelling description to attract customers</p>
                                <span id="char-count" class="text-xs text-gray-500">0 characters</span>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-error flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Location, Price, Duration, Capacity Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                    Location
                                </label>
                                <div class="relative">
                                    <input type="text" name="location" id="location" 
                                           value="{{ old('location', $tour->location ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                           placeholder="Enter tour location">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <i class="fas fa-location-arrow"></i>
                                    </div>
                                </div>
                                @error('location')
                                    <p class="mt-2 text-sm text-error flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-tag mr-2 text-primary"></i>
                                    Price *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">Rp</span>
                                    </div>
                                    <input type="number" name="price" id="price" step="1000" min="0" 
                                           value="{{ old('price', $tour->price ?? '') }}"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                           placeholder="0"
                                           required>
                                </div>
                                @error('price')
                                    <p class="mt-2 text-sm text-error flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Duration -->
                            <div>
                                <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="far fa-clock mr-2 text-primary"></i>
                                    Duration (Days) *
                                </label>
                                <div class="relative">
                                    <input type="number" name="duration_days" id="duration_days" min="1" 
                                           value="{{ old('duration_days', $tour->duration_days ?? 1) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                           required>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <span class="text-gray-500">days</span>
                                    </div>
                                </div>
                                @error('duration_days')
                                    <p class="mt-2 text-sm text-error flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-users mr-2 text-primary"></i>
                                    Capacity
                                </label>
                                <div class="relative">
                                    <input type="number" name="capacity" id="capacity" min="1" 
                                           value="{{ old('capacity', $tour->capacity ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                                           placeholder="Leave empty for unlimited">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Maximum number of guests per schedule</p>
                                @error('capacity')
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
                                       {{ old('is_active', isset($tour) ? $tour->is_active : true) ? 'checked' : '' }}
                                       class="h-5 w-5 text-primary focus:ring-primary border-gray-300 rounded checkbox-custom">
                            </div>
                            <div class="ml-3">
                                <label for="is_active" class="text-sm font-medium text-gray-700">
                                    Active Tour
                                </label>
                                <p class="text-sm text-gray-500 mt-1">
                                    When checked, this tour will be visible to customers for booking.
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

                    <!-- Right Column - Image Upload & Preview -->
                    <div class="space-y-6">
                        <!-- Thumbnail Upload -->
                        <div>
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-image mr-2 text-primary"></i>
                                Thumbnail Image
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition-colors">
                                <div id="upload-area" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-primary">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 5MB</p>
                                </div>
                                <input type="file" name="thumbnail" id="thumbnail" 
                                       class="hidden"
                                       accept="image/*">
                            </div>
                            @error('thumbnail')
                                <p class="mt-2 text-sm text-error flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Image Preview -->
                        <div id="image-preview-container" class="{{ isset($tour) && $tour->thumbnail ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Image Preview</label>
                            <div class="relative">
                                <img id="image-preview" 
                                     src="{{ isset($tour) && $tour->thumbnail ? asset('storage/' . $tour->thumbnail) : '#' }}" 
                                     alt="Tour thumbnail preview" 
                                     class="w-full h-48 object-cover rounded-lg image-preview border border-gray-200">
                                <button type="button" id="remove-image" class="absolute top-2 right-2 bg-error text-white p-1 rounded-full hover:bg-red-600 transition-colors">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Click the X to remove the image</p>
                        </div>

                        <!-- Current Image (for edit) -->
                        @if(isset($tour) && $tour->thumbnail)
                            <div id="current-image-container">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Current Image</label>
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $tour->thumbnail) }}" 
                                         alt="{{ $tour->name }}" 
                                         class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">This is the current image. Upload a new one to replace it.</p>
                            </div>
                        @endif

                        <!-- Price Preview -->
                        <div class="price-preview rounded-lg p-4 text-white">
                            <h3 class="font-semibold mb-2">Price Preview</h3>
                            <div class="text-2xl font-bold" id="price-preview">Rp 0</div>
                            <p class="text-sm opacity-90 mt-1" id="duration-preview">1 day tour</p>
                            <p class="text-xs opacity-80 mt-2">As displayed to customers</p>
                        </div>

                        <!-- Form Tips -->
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                                <i class="fas fa-lightbulb mr-2"></i>
                                Form Tips
                            </h4>
                            <ul class="text-xs text-blue-600 space-y-1">
                                <li>• Use high-quality images for better conversion</li>
                                <li>• Write compelling descriptions with key highlights</li>
                                <li>• Set appropriate capacity to manage group sizes</li>
                                <li>• Price competitively based on market research</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('admin.tours.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center justify-center transition">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="btn-primary-custom px-6 py-3 text-white rounded-lg text-sm font-medium flex items-center justify-center transition shadow-md hover:shadow-lg">
                        <i class="fas fa-{{ isset($tour) ? 'save' : 'plus' }} mr-2"></i>
                        {{ isset($tour) ? 'Update Tour' : 'Create Tour' }}
                    </button>
                </div>
            </form>
        </div>
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
    
    .image-preview {
        transition: all 0.3s ease;
    }
    
    .image-preview:hover {
        transform: scale(1.05);
    }
    
    .price-preview {
        background: #010d4c;
        box-shadow: 0 15px 35px rgba(1, 13, 76, 0.35);
    }

    .card-custom {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--tb-primary) 0%, var(--tb-primary-dark) 100%);
        color: #ffffff;
    }

    .btn-primary-custom:hover {
        filter: brightness(1.03);
        transform: translateY(-1px);
        box-shadow: 0 18px 40px rgba(1, 13, 76, 0.35);
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
    // Character count for description
    const description = document.getElementById('description');
    const charCount = document.getElementById('char-count');
    
    description.addEventListener('input', function() {
        charCount.textContent = this.value.length + ' characters';
    });
    
    // Trigger on page load
    description.dispatchEvent(new Event('input'));

    // Image upload handling
    const uploadArea = document.getElementById('upload-area');
    const thumbnailInput = document.getElementById('thumbnail');
    const imagePreview = document.getElementById('image-preview');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const removeImageBtn = document.getElementById('remove-image');
    const currentImageContainer = document.getElementById('current-image-container');

    uploadArea.addEventListener('click', function() {
        thumbnailInput.click();
    });

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-primary', 'bg-primary-light', 'bg-opacity-10');
    });

    uploadArea.addEventListener('dragleave', function() {
        uploadArea.classList.remove('border-primary', 'bg-primary-light', 'bg-opacity-10');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-primary', 'bg-primary-light', 'bg-opacity-10');
        
        if (e.dataTransfer.files.length) {
            thumbnailInput.files = e.dataTransfer.files;
            updateImagePreview(e.dataTransfer.files[0]);
        }
    });

    thumbnailInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            updateImagePreview(this.files[0]);
            
            // Hide current image if we're in edit mode
            if (currentImageContainer) {
                currentImageContainer.classList.add('hidden');
            }
        }
    });

    removeImageBtn.addEventListener('click', function() {
        thumbnailInput.value = '';
        imagePreviewContainer.classList.add('hidden');
        
        // Show current image again if we're in edit mode
        if (currentImageContainer) {
            currentImageContainer.classList.remove('hidden');
        }
    });

    function updateImagePreview(file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreviewContainer.classList.remove('hidden');
        }
        
        reader.readAsDataURL(file);
    }

    // Price and duration preview
    const priceInput = document.getElementById('price');
    const durationInput = document.getElementById('duration_days');
    const pricePreview = document.getElementById('price-preview');
    const durationPreview = document.getElementById('duration-preview');

    function updatePricePreview() {
        const price = priceInput.value ? parseInt(priceInput.value).toLocaleString('id-ID') : '0';
        pricePreview.textContent = 'Rp ' + price;
    }

    function updateDurationPreview() {
        const days = durationInput.value || 1;
        durationPreview.textContent = days + (days == 1 ? ' day tour' : ' days tour');
    }

    priceInput.addEventListener('input', updatePricePreview);
    durationInput.addEventListener('input', updateDurationPreview);

    // Initialize on page load
    updatePricePreview();
    updateDurationPreview();
</script>
@endpush
