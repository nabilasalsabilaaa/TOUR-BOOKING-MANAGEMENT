<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminBookingController extends Controller
{
    protected WhatsAppService $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Tampilkan daftar semua booking untuk admin.
     */
    public function index()
    {
        // Ambil semua booking beserta relasi user & tour (via schedule)
        $bookings = Booking::with(['user', 'schedule.tour'])
            ->latest()
            ->paginate(15);

        // Sesuaikan dengan nama view-mu
        // contoh: resources/views/admin/bookings/index.blade.php
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Konfirmasi booking dengan WhatsApp notification
     */
    public function confirm(Booking $booking)
    {
        try {
            DB::beginTransaction();
            
            // Update status booking
            $booking->update([
                'status'       => 'confirmed',
                'confirmed_at' => now(),
            ]);

            // Kirim notifikasi WhatsApp
            $this->sendConfirmationNotification($booking);
            
            DB::commit();
            
            return back()->with('success', 'Booking berhasil dikonfirmasi dan notifikasi WA dikirim.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 
                'Booking dikonfirmasi tetapi gagal mengirim WA: ' . $e->getMessage()
            );
        }
    }

    /**
     * Batalkan booking dengan WhatsApp notification
     */
    public function cancel(Booking $booking)
    {
        try {
            DB::beginTransaction();
            
            // Kembalikan slot
            if ($booking->schedule) {
                $booking->schedule->increment('available_slots', $booking->guests);
            }
            
            // Update status
            $booking->update([
                'status'        => 'cancelled',
                'cancelled_at'  => now(),
            ]);

            // Kirim notifikasi WhatsApp
            $this->sendCancellationNotification($booking);
            
            DB::commit();
            
            return back()->with('success', 
                'Booking dibatalkan, slot dikembalikan, dan notifikasi WA dikirim.'
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 
                'Gagal membatalkan booking: ' . $e->getMessage()
            );
        }
    }

    /**
     * Kirim notifikasi konfirmasi via WhatsApp
     */
    private function sendConfirmationNotification(Booking $booking): bool
    {
        $booking->load(['user', 'schedule.tour']);
        
        $message = 
            "✅ *BOOKING DIKONFIRMASI*\n\n" .
            "Halo *{$booking->customer_name}*,\n" .
            "Booking tour Anda telah *DIKONFIRMASI* oleh admin.\n\n" .
            "📋 *Detail Booking:*\n" .
            "• Tour: {$booking->schedule->tour->name}\n" .
            "• Tanggal: " . date('d/m/Y', strtotime($booking->schedule->date)) . "\n" .
            "• Jam: {$booking->schedule->start_time}\n" .
            "• Peserta: {$booking->guests} orang\n" .
            "• Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n" .
            "Terima kasih, selamat berwisata! 🎉";
        
        // Service minimal: cuma 2 parameter (phone, message)
        $result = $this->whatsapp->sendMessage(
            $booking->customer_phone,
            $message
        );
        
        Log::info('WhatsApp Confirmation Sent', [
            'booking_id' => $booking->id,
            'phone'      => $booking->customer_phone,
            'success'    => $result['success'] ?? false,
            'response'   => $result,
        ]);
        
        return $result['success'] ?? false;
    }

    /**
     * Kirim notifikasi pembatalan via WhatsApp
     */
    private function sendCancellationNotification(Booking $booking): bool
    {
        $booking->load(['user', 'schedule.tour']);
        
        $message = 
            "❌ *BOOKING DIBATALKAN*\n\n" .
            "Halo *{$booking->customer_name}*,\n" .
            "Booking tour Anda telah *DIBATALKAN*.\n\n" .
            "📋 *Detail Booking:*\n" .
            "• Tour: {$booking->schedule->tour->name}\n" .
            "• Tanggal: " . date('d/m/Y', strtotime($booking->schedule->date)) . "\n" .
            "• Peserta: {$booking->guests} orang\n" .
            "• Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n" .
            "💡 *Informasi:*\n" .
            "Dana akan dikembalikan dalam 1-3 hari kerja ke rekening asal.\n\n" .
            "Mohon maaf atas ketidaknyamanannya.";
        
        $result = $this->whatsapp->sendMessage(
            $booking->customer_phone,
            $message
        );
        
        Log::info('WhatsApp Cancellation Sent', [
            'booking_id' => $booking->id,
            'phone'      => $booking->customer_phone,
            'success'    => $result['success'] ?? false,
            'response'   => $result,
        ]);
        
        return $result['success'] ?? false;
    }
}
