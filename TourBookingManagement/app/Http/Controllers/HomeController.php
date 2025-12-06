<?php

namespace App\Http\Controllers;

use App\Models\Tour;

/**
 * HomeController
 *
 * Mengelola halaman utama (Landing Page) sistem.
 * Menampilkan daftar tour paling populer berdasarkan jumlah booking.
 */
class HomeController extends Controller
{
    /**
     * Menampilkan halaman home.
     *
     * Data yang ditampilkan:
     * - Tour dengan booking terbanyak (popular tours)
     */
    public function index()
    {
        // Ambil 6 tour paling populer berdasarkan jumlah booking terbanyak
        $popularTours = Tour::withCount('bookings')   // hitung total booking per tour
            ->orderBy('bookings_count', 'desc')       // urutkan dari yang paling banyak booking
            ->take(6)                                 // batasi 6 item untuk ditampilkan di homepage
            ->get();

        return view('layouts.home', compact('popularTours'));
    }
}
