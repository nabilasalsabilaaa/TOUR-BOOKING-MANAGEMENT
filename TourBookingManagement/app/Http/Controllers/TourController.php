<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;

/**
 * TourController (Public)
 *
 * Mengelola tampilan daftar tour dan detail tour
 * untuk pengunjung / customer (bukan admin).
 */
class TourController extends Controller
{
    /**
     * Menampilkan daftar tour yang aktif.
     *
     * Fitur:
     * - Hanya menampilkan tour dengan status is_active = true
     * - Pencarian berdasarkan nama / lokasi
     * - Sorting berdasarkan harga (asc/desc) atau terbaru (default)
     * - Pagination 9 item per halaman
     */
    public function index(Request $request)
    {
        // Simpan nilai search & sort dari query string (untuk dikirim kembali ke view)
        $search = $request->get('search');
        $sort   = $request->get('sort');

        // Query dasar: hanya tour aktif
        $query = Tour::query()
            ->where('is_active', true)
            ->withCount([
                'schedules' => function ($q) {
                    // Hitung hanya jadwal yang aktif
                    $q->where('is_active', true);
                },
            ]);

        // 🔍 Filter opsional: pencarian nama / lokasi
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // ⬆⬇ Sorting opsional berdasarkan parameter "sort"
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');   // harga termurah dulu
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');  // harga termahal dulu
                break;

            default:
                // Default: urutkan tour dari yang terbaru
                $query->latest();
        }

        // Pagination 9 tour per halaman + pertahankan query string (search, sort)
        $tours = $query->paginate(9)->withQueryString();

        return view('tours.index', compact('tours', 'search', 'sort'));
    }

    /**
     * Menampilkan detail satu tour.
     *
     * Sekaligus memuat jadwal aktif yang terkait dengan tour tersebut.
     */
    public function show(Tour $tour)
    {
        // Load jadwal aktif saja, diurutkan dari tanggal terdekat
        $tour->load([
            'schedules' => function ($q) {
                $q->where('is_active', true)
                  ->orderBy('date', 'asc');
            },
        ]);

        return view('tours.show', compact('tour'));
    }
}
