{{-- 
    Edit Profile Page
    File: resources/views/profile/edit.blade.php
    Controller: ProfileController@edit (mengirim data $user)
    Routes: 
      - GET  /profile -> profile.edit
      - PATCH /profile -> profile.update
      - PUT /password -> password.update
      - DELETE /profile -> profile.destroy
--}}

{{-- Extends main layout --}}
@extends('layouts.app')

{{-- Set page title --}}
@section('title', 'Edit Profile')

{{-- Main content section --}}
@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Profile Settings</h1>
        <p class="text-gray-600 mt-2">Manage your account information</p>
    </div>

    {{-- Three main sections in vertical stack --}}
    <div class="space-y-6">
        <!-- ==================== SECTION 1: PROFILE INFORMATION ==================== -->
        <div class="card-custom p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Information</h2>
            <p class="text-sm text-gray-600 mb-6">Update your account's profile information and email address.</p>

            {{-- Form for updating profile info (name, email, phone) --}}
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                {{-- Method spoofing untuk PATCH request --}}
                @method('patch')

                <div class="space-y-4">
                    {{-- Name field --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent"
                               required autofocus autocomplete="name">
                        {{-- Validation error display --}}
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email field --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent"
                               required autocomplete="username">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone field (optional) --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent"
                               autocomplete="tel">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email verification notice (jika user belum verifikasi email) --}}
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm text-gray-800">
                                Your email address is unverified.
                                {{-- Form tersembunyi untuk mengirim ulang verifikasi --}}
                                <button form="send-verification" class="text-primary hover:underline">
                                    Click here to re-send the verification email.
                                </button>
                            </p>

                            {{-- Success message setelah link verifikasi dikirim --}}
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm text-green-600">
                                    A new verification link has been sent to your email address.
                                </p>
                            @endif
                        </div>
                    @endif

                    {{-- Submit button dan success message --}}
                    <div class="flex items-center gap-4">
                        <button type="submit" class="btn-primary-custom">
                            Save Changes
                        </button>

                        {{-- Success indicator ketika profile berhasil diupdate --}}
                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-green-600">Saved.</p>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- ==================== SECTION 2: UPDATE PASSWORD ==================== -->
        <div class="card-custom p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Update Password</h2>
            <p class="text-sm text-gray-600 mb-6">Ensure your account is using a long, random password to stay secure.</p>

            {{-- Form untuk update password --}}
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                {{-- Method spoofing untuk PUT request --}}
                @method('put')

                <div class="space-y-4">
                    {{-- Current password --}}
                    <div>
                        <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" id="update_password_current_password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent"
                               autocomplete="current-password">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New password --}}
                    <div>
                        <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password" id="update_password_password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent"
                               autocomplete="new-password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm new password --}}
                    <div>
                        <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="update_password_password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent"
                               autocomplete="new-password">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit button dan success message --}}
                    <div class="flex items-center gap-4">
                        <button type="submit" class="btn-primary-custom">
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-green-600">Saved.</p>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- ==================== SECTION 3: DELETE ACCOUNT ==================== -->
        <div class="card-custom p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Delete Account</h2>
            <p class="text-sm text-gray-600 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

            {{-- Button trigger untuk modal konfirmasi delete --}}
            <button type="button" 
                    class="text-red-600 hover:text-red-900 font-medium"
                    onclick="confirmDelete()">
                Delete Account
            </button>

            {{-- Modal konfirmasi delete (hidden by default) --}}
            <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        {{-- Icon peringatan --}}
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Delete Account</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete your account? This action cannot be undone.
                            </p>
                        </div>
                        {{-- Form delete dengan dua button: cancel dan delete --}}
                        <div class="items-center px-4 py-3">
                            <form method="POST" action="{{ route('profile.destroy') }}" class="inline">
                                @csrf
                                @method('delete')
                                <div class="flex justify-center space-x-3">
                                    <button type="button" 
                                            class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400"
                                            onclick="closeDeleteModal()">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                                        Delete Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript untuk mengelola modal delete --}}
<script>
    // Menampilkan modal delete
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Menutup modal delete
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Menutup modal jika user klik di luar modal
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    }
</script>
@endsection