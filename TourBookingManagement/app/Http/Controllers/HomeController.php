<?php

namespace App\Http\Controllers;

use App\Models\Tour;

class HomeController extends Controller
{
    public function index()
    {
        // Tour dengan booking terbanyak
        $popularTours = Tour::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(6) // ambil 6 tour
            ->get();

        return view('layouts.home', compact('popularTours'));
    }
}
