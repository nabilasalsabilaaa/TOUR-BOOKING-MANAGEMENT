<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Booking;

/**
 * DashboardController
 *
 * Controller ini menangani data yang ditampilkan pada halaman Dashboard Admin,
 * seperti total tour, total booking, booking pending, revenue, dan booking terbaru.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     * Di sini dilakukan perhitungan untuk menampilkan statistik utama.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 📌 Total jumlah tour yang tersedia
        $totalTours = Tour::count();

        // 📌 Total seluruh booking yang masuk tanpa melihat status
        $totalBookings = Booking::count();

        // 📌 Total booking yang masih berstatus pending (belum dikonfirmasi)
        $pendingBookings = Booking::where('status', 'pending')->count();

        // 📌 Total pendapatan (hanya booking yang sudah confirmed)
        $revenue = Booking::where('status', 'confirmed')->sum('total_price');

        // 📌 Ambil 5 booking terbaru untuk ditampilkan pada dashboard
        $recentBookings = Booking::with(['schedule.tour'])
            ->latest()
            ->take(5)
            ->get();

        // Kirim semua variabel ke view dashboard
        return view('admin.dashboard', compact(
            'totalTours',
            'totalBookings',
            'pendingBookings',
            'revenue',
            'recentBookings'
        ));
    }
}