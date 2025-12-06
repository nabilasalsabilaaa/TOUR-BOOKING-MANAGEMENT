<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
// Public tours (katalog untuk user umum / customer)
use App\Http\Controllers\TourController; // <- yang NON-admin

// Admin controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\TourScheduleController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

// Halaman utama/landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Detail tour individual (bisa dilihat siapa saja)
Route::get('/tours/{tour}', [TourController::class, 'show'])
    ->name('tours.show');

// Katalog tour yang bisa dilihat semua orang / customer
Route::get('/tours', [TourController::class, 'index'])
    ->name('tours.index');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (Role-based)
|--------------------------------------------------------------------------
|
| Route untuk /dashboard yang akan mengarahkan user ke dashboard
| berdasarkan role mereka (admin atau customer)
|
*/
Route::get('/dashboard', function () {
    // Redirect ke login jika belum terautentikasi
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    // Redirect berdasarkan role user
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Default redirect untuk customer
    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (Semua user login bisa akses)
|--------------------------------------------------------------------------
|
| Routes untuk mengelola profil user (edit, update, delete)
|
*/
Route::middleware('auth')->group(function () {
    // Form edit profil
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // Update profil
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Hapus akun
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Cancel booking (customer)
    Route::patch('/customer/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('customer.bookings.cancel');
});

/*
|--------------------------------------------------------------------------
| Customer Routes (Hanya untuk user dengan role 'customer')
|--------------------------------------------------------------------------
|
| Semua route dalam grup ini:
| - Memerlukan autentikasi
| - Hanya bisa diakses oleh user dengan role 'customer'
| - Memiliki prefix '/customer' di URL
| - Memiliki nama route dengan prefix 'customer.'
|
*/
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        // Dashboard customer - menampilkan ringkasan booking dll
        Route::get('/dashboard', [BookingController::class, 'customerDashboard'])
            ->name('dashboard');

        // List semua booking milik customer
        Route::get('/bookings', [BookingController::class, 'index'])
            ->name('bookings.index');

        // Form untuk membuat booking baru berdasarkan schedule tertentu
        Route::get('/bookings/create/{schedule}', [BookingController::class, 'create'])
            ->name('bookings.create');

        // Menyimpan booking baru ke database
        Route::post('/bookings/{schedule}', [BookingController::class, 'store'])
            ->name('bookings.store');

        // Detail booking spesifik
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])
            ->name('bookings.show');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (Hanya untuk user dengan role 'admin')
|--------------------------------------------------------------------------
|
| Semua route dalam grup ini:
| - Memerlukan autentikasi
| - Hanya bisa diakses oleh user dengan role 'admin'
| - Memiliki prefix '/admin' di URL
| - Memiliki nama route dengan prefix 'admin.'
|
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin - menampilkan statistik dan overview
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD lengkap untuk paket tour (index, create, store, show, edit, update, destroy)
        Route::resource('tours', AdminTourController::class);

        // CRUD lengkap untuk jadwal tour
        // Parameter diubah dari 'schedules' menjadi 'schedule' untuk konsistensi
        Route::resource('schedules', TourScheduleController::class)
            ->parameters([
                'schedules' => 'schedule',
            ]);

        // Manajemen booking (bukan resource penuh, hanya beberapa aksi)
        Route::get('bookings', [AdminBookingController::class, 'index'])
            ->name('bookings.index');

        Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])
            ->name('bookings.show');

        // Konfirmasi booking
        Route::post('bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])
            ->name('bookings.confirm');

        // Batalkan booking (oleh admin)
        Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])
            ->name('bookings.cancel');
    });

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Routes untuk autentikasi (login, register, password reset, dll)
| Diambil dari file auth.php yang di-generate oleh Laravel Breeze/Jetstream
|
*/
require __DIR__ . '/auth.php';