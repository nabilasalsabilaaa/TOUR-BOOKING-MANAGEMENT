<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    protected WhatsAppService $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function index(Request $request)
    {
        $query = Booking::with(['user', 'schedule.tour'])
            ->latest(); 

        // 🔍 Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('schedule.tour', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 📌 Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // 📅 Date filter
        if ($date = $request->input('date')) {
            $query->whereHas('schedule', function ($q) use ($date) {
                $q->whereDate('date', $date);
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'schedule.tour']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        $booking->load(['schedule.tour']);

        $booking->update([
            'status' => 'confirmed',
        ]);

        // WhatsApp notif
        $to = $this->whatsapp->normalizePhone($booking->customer_phone);

        $msg =
            "*Booking Dikonfirmasi ✅*\n\n" .
            "Halo {$booking->customer_name},\n" .
            "Booking Anda telah *DIKONFIRMASI*.\n\n" .
            "Detail:\n" .
            "- Tour: {$booking->schedule->tour->name}\n" .
            "- Tanggal: {$booking->schedule->date}\n" .
            "- Peserta: {$booking->guests}\n" .
            "- Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n" .
            "Sampai jumpa di hari keberangkatan!";

        $this->whatsapp->sendMessage($to, $msg);

        return back()->with('success', 'Booking berhasil dikonfirmasi dan notifikasi WA dikirim.');
    }

    public function cancel(Booking $booking)
    {
        $booking->load(['schedule.tour']);

        DB::transaction(function () use ($booking) {
            if ($booking->schedule) {
                $booking->schedule->increment('available_slots', $booking->guests);
            }

            $booking->update([
                'status' => 'cancelled',
            ]);
        });

        // WhatsApp message
        $to = $this->whatsapp->normalizePhone($booking->customer_phone);

        $msg =
            "*Booking DIBATALKAN ❌*\n\n" .
            "Halo {$booking->customer_name},\n" .
            "Mohon maaf, booking Anda telah dibatalkan.\n\n" .
            "Detail:\n" .
            "- Tour: {$booking->schedule->tour->name}\n" .
            "- Tanggal: {$booking->schedule->date}\n" .
            "- Peserta: {$booking->guests}\n\n" .
            "Jika ini tidak sesuai, silakan hubungi admin.";

        $this->whatsapp->sendMessage($to, $msg);

        return back()->with('success', 'Booking dibatalkan, slot dikembalikan, dan WA dikirim.');
    }
}
