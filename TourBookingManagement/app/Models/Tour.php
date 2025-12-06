<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'price',
        'duration_days',
        'capacity',
        'thumbnail',
        'is_active',
    ];

    protected $casts = [
        'price'         => 'integer',
        'duration_days' => 'integer',
        'capacity'      => 'integer',
        'is_active'     => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(TourSchedule::class);
    }

    public function activeSchedules()
    {
        return $this->schedules()
            ->where('is_active', true)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date');
    }

    // Scope untuk tour aktif (buat katalog)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function bookings()
    {
        // semua booking yang lewat schedule tour ini
        return $this->hasManyThrough(Booking::class, TourSchedule::class);
    }
}
