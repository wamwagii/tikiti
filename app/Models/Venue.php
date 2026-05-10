<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'city',
        'address',
        'capacity',
        'description',
        'image',
        'amenities',
        'contact_info',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'contact_info' => 'array',
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    /**
     * Get events at this venue
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get upcoming events at this venue
     */
    public function upcomingEvents(): HasMany
    {
        return $this->hasMany(Event::class)->where('start_date', '>', now())->where('status', 'published');
    }

    /**
     * Check if venue is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get full location string
     */
    public function getFullLocationAttribute(): string
    {
        return $this->location . ', ' . $this->city;
    }

    /**
     * Get capacity display
     */
    public function getCapacityDisplayAttribute(): string
    {
        return $this->capacity ? number_format($this->capacity) . ' seats' : 'Not specified';
    }
}