<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * TourController
 *
 * Mengelola CRUD paket tour:
 * - List tour
 * - Tambah tour
 * - Edit tour
 * - Update tour
 * - Hapus tour
 */
class TourController extends Controller
{
    /**
     * Menampilkan daftar paket tour.
     * Termasuk menampilkan jumlah schedule & booking terkait.
     */
    public function index()
    {
        $tours = Tour::withCount('schedules')   // Hitung total jadwal per tour
            ->withCount('bookings')             // Hitung total booking per tour
            ->latest()                          // Urutkan dari terbaru
            ->paginate(10);                     // Pagination 10 per halaman

        return view('admin.tours.index', compact('tours'));
    }

    /**
     * Menampilkan form untuk membuat tour baru.
     */
    public function create()
    {
        return view('admin.tours.create');
    }

    /**
     * Menyimpan data tour baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
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

        // Generate slug unik agar URL tidak bentrok
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);

        // Konversi checkbox is_active menjadi boolean
        $data['is_active'] = $request->boolean('is_active');

        // Upload gambar jika ada
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('tours', 'public');
        }

        // Simpan data tour baru
        Tour::create($data);

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Paket tour berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit untuk tour tertentu.
     */
    public function edit(Tour $tour)
    {
        return view('admin.tours.edit', compact('tour'));
    }

    /**
     * Mengupdate data tour yang sudah ada.
     */
    public function update(Request $request, Tour $tour)
    {
        // Validasi input
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

        // Konversi checkbox
        $data['is_active'] = $request->boolean('is_active');

        // Jika ada thumbnail baru, upload dan replace file lama
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('tours', 'public');
        }

        // Update tour
        $tour->update($data);

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Paket tour berhasil diperbarui.');
    }

    /**
     * Menghapus tour dari database.
     * Catatan: Jika tour memiliki schedule/booking, sebaiknya diberi handling tambahan.
     */
    public function destroy(Tour $tour)
    {
        $tour->delete();

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Paket tour berhasil dihapus.');
    }
}