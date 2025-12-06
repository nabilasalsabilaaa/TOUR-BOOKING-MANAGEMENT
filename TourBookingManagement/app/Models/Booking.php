<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tour_schedule_id',
        'guests',
        'total_price',
        'status',
        'customer_name',
        'customer_phone',
        'notes',
    ];

    protected $casts = [
        'guests'      => 'integer',
        'total_price' => 'integer',
    ];

    // Optional: daftar status supaya konsisten
    public const STATUS_PENDING   = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(TourSchedule::class, 'tour_schedule_id');
    }

    public function tour()
    {
        // Shortcut kalau mau akses langsung tour dari booking
        return $this->hasOneThrough(
            Tour::class,
            TourSchedule::class,
            'id',              // foreign key di schedules
            'id',              // foreign key di tours
            'tour_schedule_id',// local key di bookings
            'tour_id'          // local key di schedules
        );
    }

    // Scope kecil-kecilan (opsional)
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED])
            ->whereHas('schedule', function ($q) {
                $q->whereDate('date', '>=', now()->toDateString());
            });
    }

    // Booking.php
public function tourSchedule()
{
    return $this->belongsTo(TourSchedule::class);
}

}
