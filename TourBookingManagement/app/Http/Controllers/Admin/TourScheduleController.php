<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourSchedule;
use Illuminate\Http\Request;

class TourScheduleController extends Controller
{
    public function index(Request $request)
    {
        // kalau datang dari /admin/schedules?tour=4
        $tourId = $request->get('tour');

        // Query dasar untuk LIST (tabel)
        $listQuery = TourSchedule::query()
            ->with(['tour', 'bookings']) // tour & bookings biar ga N+1
            ->withCount('bookings')      // $schedule->bookings_count
            ->orderBy('date', 'asc');

        // Filter per tour kalau ada ?tour=ID
        if ($tourId) {
            $listQuery->where('tour_id', $tourId);
        }

        // Query terpisah untuk STATISTIK (pakai collection, bukan paginator)
        $statsQuery = TourSchedule::query()
            ->with(['tour'])
            ->withCount('bookings');

        if ($tourId) {
            $statsQuery->where('tour_id', $tourId);
        }

        $allSchedules = $statsQuery->get();                       // untuk statistik
        $schedules    = $listQuery->paginate(10)->withQueryString(); // untuk tabel

        // list tour untuk kebutuhan label / filter info
        $tours = Tour::orderBy('name')->get();

        return view('admin.schedules.index', compact(
            'schedules',
            'allSchedules',
            'tours',
            'tourId'
        ));
    }


    public function create()
    {
        $tours = Tour::where('is_active', 1)->get();

        return view('admin.schedules.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tour_id'         => 'required|exists:tours,id',
            'date'            => 'required|date|after_or_equal:today',
            'available_slots' => 'required|integer|min:1',
            'is_active'       => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        TourSchedule::create($data);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal tour berhasil ditambahkan.');
    }

    public function edit(TourSchedule $schedule)
    {
        $tours = Tour::where('is_active', 1)->get();

        return view('admin.schedules.edit', compact('schedule', 'tours'));
    }

    public function update(Request $request, TourSchedule $schedule)
    {
        $data = $request->validate([
            'tour_id'         => 'required|exists:tours,id',
            'date'            => 'required|date|after_or_equal:today',
            'available_slots' => 'required|integer|min:1',
            'is_active'       => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $schedule->update($data);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal tour berhasil diperbarui.');
    }

    public function destroy(TourSchedule $schedule)
    {
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal tour berhasil dihapus.');
    }
}
