<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Tour;

class TourController extends Controller
{

    public function index(Request $request)
    {
        $query = Tour::query()
            ->where('is_active', true)
            ->withCount(['schedules' => function ($q) {
                $q->where('is_active', true);
            }]);

        // optional: search by name / location
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // optional: sort
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $tours = $query->paginate(9)->withQueryString();

        return view('tours.index', compact('tours', 'search'));
    }
    public function show(Tour $tour)
    {
        // Load jadwal aktif kalau mau
        $tour->load(['schedules' => function ($q) {
            $q->where('is_active', true)
              ->orderBy('date', 'asc');
        }]);

        return view('tours.show', compact('tour'));
    }
}
