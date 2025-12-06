<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::withCount('schedules')
            ->withCount('bookings') 
            ->latest()
            ->paginate(10);

        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        return view('admin.tours.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'location'      => 'nullable|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'capacity'      => 'nullable|integer|min:1',
            'thumbnail'     => 'nullable|image|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('tours', 'public');
        }

        Tour::create($data);

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Paket tour berhasil ditambahkan.');
    }

    public function edit(Tour $tour)
    {
        return view('admin.tours.edit', compact('tour'));
    }

    public function update(Request $request, Tour $tour)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'location'      => 'nullable|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'capacity'      => 'nullable|integer|min:1',
            'thumbnail'     => 'nullable|image|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('tours', 'public');
        }

        $tour->update($data);

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Paket tour berhasil diperbarui.');
    }

    public function destroy(Tour $tour)
    {
        $tour->delete();

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Paket tour berhasil dihapus.');
    }
}
