<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTours = Tour::count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $revenue = Booking::where('status', 'confirmed')->sum('total_price');

        $recentBookings = Booking::with(['schedule.tour'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTours',
            'totalBookings',
            'pendingBookings',
            'revenue',
            'recentBookings'
        ));
    }
}
