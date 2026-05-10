<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'venue_id',
        'start_date',
        'end_date',
        'image',
        'base_price',
        'status',
        'featured',
        'ticket_tiers',
        'total_tickets',
        'tickets_sold',
        'tickets_available',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'base_price' => 'float',
        'featured' => 'boolean',
        'total_tickets' => 'integer',
        'tickets_sold' => 'integer',
        'tickets_available' => 'integer',
    ];

    // Event types
    const TYPE_FOOTBALL = 'football';
    const TYPE_CONCERT = 'concert';
    const TYPE_OTHER = 'other';

    // Status types
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    /**
     * Accessor for ticket_tiers - ensures we always get an array
     */
    public function getTicketTiersAttribute($value)
    {
        Log::info('getTicketTiersAttribute called', [
            'value_type' => gettype($value),
            'value' => $value
        ]);
        
        if (is_null($value)) {
            return [];
        }
        
        // Try to decode as JSON
        $decoded = json_decode($value, true);
        
        // If it's still a string (double encoded), decode again
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }
        
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Mutator for ticket_tiers - Simplified version
     */
    public function setTicketTiersAttribute($value)
    {
        Log::info('setTicketTiersAttribute called', [
            'value_type' => gettype($value),
            'value' => $value
        ]);
        
        // If it's null, store an empty array
        if (is_null($value)) {
            $this->attributes['ticket_tiers'] = json_encode([]);
            Log::info('Set ticket_tiers to empty array');
            return;
        }
        
        // If it's an array, encode to JSON
        if (is_array($value)) {
            $this->attributes['ticket_tiers'] = json_encode($value);
            Log::info('Set ticket_tiers from array', ['encoded' => $this->attributes['ticket_tiers']]);
            return;
        }
        
        // If it's a string, store it as is (assuming it's already JSON)
        if (is_string($value)) {
            // Validate it's valid JSON
            json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->attributes['ticket_tiers'] = $value;
                Log::info('Set ticket_tiers from string (valid JSON)', ['value' => $value]);
            } else {
                $this->attributes['ticket_tiers'] = json_encode([]);
                Log::warning('Invalid JSON string provided, set to empty array');
            }
            return;
        }
        
        // Default fallback
        $this->attributes['ticket_tiers'] = json_encode([]);
        Log::warning('Unknown value type for ticket_tiers, set to empty array');
    }

    /**
     * Get the venue for this event
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get orders for this event
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get tickets for this event
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Check if event is published
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Check if event has available tickets
     */
    public function hasAvailableTickets(): bool
    {
        return $this->tickets_available > 0;
    }

    /**
     * Get formatted price range
     */
    public function getPriceRangeAttribute(): string
    {
        $tiers = $this->ticket_tiers;
        if (!empty($tiers) && count($tiers) > 0) {
            $prices = collect($tiers)->pluck('price');
            return 'KES ' . number_format($prices->min(), 2) . ' - ' . number_format($prices->max(), 2);
        }
        return 'KES ' . number_format($this->base_price, 2);
    }

    /**
     * Get the type badge color
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_FOOTBALL => 'success',
            self::TYPE_CONCERT => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Get Kenyan Premier League teams
     */
    public static function getSportpesaTeams(): array
    {
        return [
            'Gor Mahia',
            'AFC Leopards',
            'KCB',
            'Tusker',
            'Bandari',
            'Kariobangi Sharks',
            'Wazito',
            'Mathare United',
            'Ulinzi Stars',
            'Sofapaka',
            'Nzoia Sugar',
            'Vihiga Bullets',
            'Kenya Police',
            'Posta Rangers',
            'Bidco United',
            'Talanta',
        ];
    }

    /**
     * Boot method to generate slug
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = \Illuminate\Support\Str::slug($event->title) . '-' . uniqid();
            }
        });
        
        // Add logging for saving events
        static::saving(function ($event) {
            Log::info('Event being saved', [
                'id' => $event->id,
                'ticket_tiers_type' => gettype($event->ticket_tiers),
                'ticket_tiers_raw' => $event->getRawOriginal('ticket_tiers')
            ]);
        });
    }
}