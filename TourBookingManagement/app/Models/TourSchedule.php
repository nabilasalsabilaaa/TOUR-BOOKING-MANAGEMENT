<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'date',
        'available_slots',
        'is_active',
    ];

    protected $casts = [
        'date'            => 'date',
        'available_slots' => 'integer',
        'is_active'       => 'boolean',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tour_schedule_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('is_active', true)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc');
    }

    public function getIsFullAttribute(): bool
    {
        return $this->available_slots <= 0;
    }
}
