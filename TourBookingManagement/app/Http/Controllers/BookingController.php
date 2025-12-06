<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TourSchedule;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * BookingController
 *
 * Mengelola alur booking dari sisi customer.
 */
class BookingController extends Controller
{
    /**
     * Service untuk kirim pesan WhatsApp.
     *
     * @var WhatsAppService
     */
    protected WhatsAppService $whatsapp;

    /**
     * Injeksi WhatsAppService ke controller.
     */
    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Dashboard pelanggan.
     */
    public function customerDashboard()
    {
        $user = Auth::user();

        $totalBookings = Booking::where('user_id', $user->id)->count();

        $confirmedBookings = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->count();

        $pendingBookings = Booking::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $recentBookings = Booking::with(['schedule.tour'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $today = now()->toDateString();

        $upcomingBookings = Booking::with(['schedule.tour'])
            ->join('tour_schedules', 'bookings.tour_schedule_id', '=', 'tour_schedules.id')
            ->where('bookings.user_id', $user->id)
            ->whereIn('bookings.status', ['pending', 'confirmed'])
            ->whereDate('tour_schedules.date', '>=', $today)
            ->orderBy('tour_schedules.date', 'asc')
            ->select('bookings.*')
            ->take(5)
            ->get();

        $totalSpent = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('total_price');

        return view('customer.dashboard', compact(
            'totalBookings',
            'confirmedBookings',
            'pendingBookings',
            'recentBookings',
            'totalSpent',
            'upcomingBookings'
        ));
    }

    /**
     * Membatalkan booking oleh customer.
     */
    public function cancel(Booking $booking)
    {
        // Pastikan booking ini milik user yang login
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya booking dengan status pending yang bisa dibatalkan
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking dengan status pending yang bisa dibatalkan.');
        }

        DB::transaction(function () use ($booking) {
            $schedule = $booking->schedule;

            if ($schedule) {
                $schedule->increment('available_slots', $booking->guests);
            }

            $booking->update([
                'status' => 'cancelled',
            ]);
        });

        // (Opsional) kirim WA ke customer
        if (isset($this->whatsapp)) {
            $to = $booking->customer_phone;

            if ($to) {
                $msg =
                    "*Booking DIBATALKAN oleh Anda ❌*\n\n" .
                    "Detail:\n" .
                    "- Tour: {$booking->schedule->tour->name}\n" .
                    "- Tanggal: {$booking->schedule->date}\n" .
                    "- Peserta: {$booking->guests}\n\n" .
                    "Terima kasih.";

                // Service minimal: langsung kirim nomor apa adanya (pastikan di DB sudah 628xxx)
                $this->whatsapp->sendMessage($to, $msg);
            }
        }

        return redirect()
            ->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan dan slot dikembalikan.');
    }

    /**
     * List booking milik user yang login.
     */
    public function index()
    {
        $bookings = Booking::with(['schedule.tour'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    /**
     * Form booking untuk suatu jadwal tertentu.
     */
    public function create(TourSchedule $schedule)
    {
        $schedule->load('tour');

        if (! $schedule->is_active) {
            abort(404, 'Jadwal tidak tersedia.');
        }

        if (Carbon::parse($schedule->date)->isPast()) {
            return back()->with('error', 'Tidak dapat booking untuk jadwal yang sudah lewat.');
        }

        if ($schedule->available_slots <= 0) {
            return back()->with('error', 'Maaf, slot untuk jadwal ini sudah penuh.');
        }

        return view('customer.bookings.create', compact('schedule'));
    }

    /**
     * Simpan booking baru + kirim WA ke customer & admin.
     */
    public function store(Request $request, TourSchedule $schedule)
    {
        $schedule->load('tour');

        $data = $request->validate([
            'guests' => 'required|integer|min:1',
            'notes'  => 'nullable|string|max:500',
        ]);

        if (Carbon::parse($schedule->date)->isPast()) {
            return back()
                ->withErrors(['guests' => 'Tidak dapat booking untuk jadwal yang sudah lewat.'])
                ->withInput();
        }

        if ($data['guests'] > $schedule->available_slots) {
            return back()
                ->withErrors(['guests' => 'Slot tersedia tidak mencukupi. Slot tersisa: ' . $schedule->available_slots])
                ->withInput();
        }

        $user = Auth::user();
        $totalPrice = $data['guests'] * $schedule->tour->price;

        if (empty($user->phone)) {
            return back()
                ->withErrors(['guests' => 'Nomor telepon belum terisi di profil. Silakan lengkapi profil terlebih dahulu.'])
                ->withInput();
        }

        $booking = null;

        try {
            DB::transaction(function () use ($schedule, $user, $data, $totalPrice, &$booking) {
                $schedule->decrement('available_slots', $data['guests']);

                $booking = Booking::create([
                    'user_id'          => $user->id,
                    'tour_schedule_id' => $schedule->id,
                    'guests'           => $data['guests'],
                    'total_price'      => $totalPrice,
                    'status'           => 'pending',
                    'customer_name'    => $user->name,
                    'customer_phone'   => $user->phone,
                    'notes'            => $data['notes'] ?? null,
                ]);
            });

            if ($booking) {
                // ==========================
                //  WhatsApp ke Customer
                // ==========================
                $to = $booking->customer_phone;

                $msgCustomer =
                    "*Booking Berhasil Dibuat 📝*\n\n" .
                    "Halo {$booking->customer_name},\n" .
                    "Terima kasih sudah melakukan booking.\n\n" .
                    "Detail Booking:\n" .
                    "- Tour: {$schedule->tour->name}\n" .
                    "- Tanggal: " . Carbon::parse($schedule->date)->format('d M Y') . "\n" .
                    "- Jumlah peserta: {$booking->guests}\n" .
                    "- Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n" .
                    "- Status: Menunggu Konfirmasi\n\n" .
                    "Kami akan mengonfirmasi booking Anda secepatnya.\n" .
                    "Terima kasih!";

                $this->whatsapp->sendMessage($to, $msgCustomer);

                // ==========================
                //  WhatsApp ke Admin (jika diset)
                // ==========================
                $adminNumber = env('ADMIN_WHATSAPP');

                if ($adminNumber) {
                    $msgAdmin =
                        "*📋 Booking Baru Masuk*\n\n" .
                        "ID Booking: #{$booking->id}\n" .
                        "Nama: {$booking->customer_name}\n" .
                        "Telepon: {$booking->customer_phone}\n" .
                        "Tour: {$schedule->tour->name}\n" .
                        "Tanggal: " . Carbon::parse($schedule->date)->format('d M Y') . "\n" .
                        "Peserta: {$booking->guests} orang\n" .
                        "Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n" .
                        "Catatan: " . ($booking->notes ?: '-') . "\n\n" .
                        "Silakan cek panel admin untuk konfirmasi.";

                    $this->whatsapp->sendMessage($adminNumber, $msgAdmin);
                }

                return redirect()
                    ->route('customer.bookings.index')
                    ->with('success', 'Booking berhasil dibuat! Status: menunggu konfirmasi. Notifikasi WA telah dikirim.');
            }
        } catch (\Throwable $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());

            return back()
                ->withErrors(['guests' => 'Terjadi kesalahan sistem. Silakan coba lagi.'])
                ->withInput();
        }

        return back()
            ->withErrors(['guests' => 'Gagal membuat booking. Silakan coba lagi.'])
            ->withInput();
    }

    /**
     * Menampilkan detail satu booking milik customer.
     */
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->load(['schedule.tour', 'user']);

        return view('customer.bookings.show', compact('booking'));
    }
}
