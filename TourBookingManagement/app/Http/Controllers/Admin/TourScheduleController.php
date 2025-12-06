<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourSchedule;
use Illuminate\Http\Request;

/**
 * TourScheduleController
 *
 * Mengelola jadwal tour:
 * - Menampilkan list jadwal + filter per tour
 * - Menampilkan statistik jadwal
 * - Membuat, mengedit, memperbarui, dan menghapus jadwal
 */
class TourScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal tour dengan fitur:
     * - Filter berdasarkan tour tertentu
     * - Statistik bookings & jadwal
     * - Pagination untuk tabel
     */
    public function index(Request $request)
    {
        // Jika datang dari /admin/schedules?tour=4 → digunakan untuk filter
        $tourId = $request->get('tour');

        /**
         * ================================
         *  QUERY UNTUK TABLE LIST JADWAL
         * ================================
         * Mengambil data jadwal lengkap dengan tour & booking (anti N+1).
         */
        $listQuery = TourSchedule::query()
            ->with(['tour', 'bookings']) // eager load agar efisien
            ->withCount('bookings')      // menambah kolom bookings_count
            ->orderBy('date', 'asc');    // tampilkan jadwal terdekat dahulu

        // Filter berdasarkan tour jika ada ?tour=ID
        if ($tourId) {
            $listQuery->where('tour_id', $tourId);
        }

        /**
         * ================================
         *    QUERY TERPISAH UNTUK STATISTIK
         * ================================
         * Tidak memakai pagination agar bisa diproses sebagai collection penuh.
         */
        $statsQuery = TourSchedule::query()
            ->with(['tour'])
            ->withCount('bookings');

        if ($tourId) {
            $statsQuery->where('tour_id', $tourId);
        }

        // Kumpulan semua jadwal untuk statistik
        $allSchedules = $statsQuery->get();

        // Data tabel utama + pagination
        $schedules = $listQuery->paginate(10)->withQueryString();

        // Mengambil semua tour aktif untuk label, filter, dropdown, dll.
        $tours = Tour::orderBy('name')->get();

        return view('admin.schedules.index', compact(
            'schedules',
            'allSchedules',
            'tours',
            'tourId'
        ));
    }

    /**
     * Menampilkan form pembuatan jadwal baru.
     */
    public function create()
    {
        // Hanya tour aktif yang boleh diberikan jadwal
        $tours = Tour::where('is_active', 1)->get();

        return view('admin.schedules.create', compact('tours'));
    }

    /**
     * Menyimpan jadwal tour baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'tour_id'         => 'required|exists:tours,id',
            'date'            => 'required|date|after_or_equal:today',
            'available_slots' => 'required|integer|min:1',
            'is_active'       => 'nullable|boolean',
        ]);

        // Handle checkbox is_active → boolean
        $data['is_active'] = $request->boolean('is_active');

        // Simpan jadwal baru
        TourSchedule::create($data);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal tour berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit jadwal tertentu.
     */
    public function edit(TourSchedule $schedule)
    {
        $tours = Tour::where('is_active', 1)->get();

        return view('admin.schedules.edit', compact('schedule', 'tours'));
    }

    /**
     * Mengupdate jadwal tour.
     */
    public function update(Request $request, TourSchedule $schedule)
    {
        // Validasi input
        $data = $request->validate([
            'tour_id'         => 'required|exists:tours,id',
            'date'            => 'required|date|after_or_equal:today',
            'available_slots' => 'required|integer|min:1',
            'is_active'       => 'nullable|boolean',
        ]);

        // Konversi checkbox ke boolean
        $data['is_active'] = $request->boolean('is_active');

        // Update jadwal
        $schedule->update($data);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal tour berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal tour.
     *
     * Catatan:
     * - Jika jadwal memiliki booking aktif, sebaiknya tambahkan guard sebelum menghapus.
     */
    public function destroy(TourSchedule $schedule)
    {
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal tour berhasil dihapus.');
    }
}