<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');



Route::get('/tours/{tour}', [TourController::class, 'show'])
    ->name('tours.show');


// Katalog tour yang bisa dilihat semua orang / customer
// Pastikan kamu punya App\Http\Controllers\TourController
Route::get('/tours', [TourController::class, 'index'])
    ->name('tours.index');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (Role-based)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile (semua user login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

        Route::patch('/customer/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
    ->name('customer.bookings.cancel');

});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        // Dashboard customer
        Route::get('/dashboard', [BookingController::class, 'customerDashboard'])
            ->name('dashboard');

        // List booking milik customer
        Route::get('/bookings', [BookingController::class, 'index'])
            ->name('bookings.index');

        // Form booking untuk jadwal tertentu
        Route::get('/bookings/create/{schedule}', [BookingController::class, 'create'])
            ->name('bookings.create');

        // Simpan booking
        Route::post('/bookings/{schedule}', [BookingController::class, 'store'])
            ->name('bookings.store');

        // Detail booking
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])
            ->name('bookings.show');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD paket tour
        Route::resource('tours', AdminTourController::class);

        // CRUD jadwal tour
        Route::resource('schedules', TourScheduleController::class)
            ->parameters([
                'schedules' => 'schedule',
            ]);

        // Manajemen booking
        Route::get('bookings', [AdminBookingController::class, 'index'])
            ->name('bookings.index');

        Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])
            ->name('bookings.show');

        Route::post('bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])
            ->name('bookings.confirm');

        Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])
            ->name('bookings.cancel');
    });

require __DIR__ . '/auth.php';
