<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    // Payment methods
    const PAYMENT_PAYSTACK = 'paystack';
    const PAYMENT_MPESA = 'mpesa';
    const PAYMENT_CARD = 'card';
    const PAYMENT_BANK = 'bank';

    protected $fillable = [
        'order_number',
        'user_id',
        'event_id',
        'status',
        'total_amount',
        'payment_method',
        'payment_reference',
        'payment_status',
        'payment_data',
        'mpesa_receipt',
        'ticket_details',
        'attendee_details',
    ];

    protected $casts = [
        'ticket_details' => 'array',
        'attendee_details' => 'array',
        'payment_data' => 'array',
        'total_amount' => 'float',
    ];

    /**
     * Get the user who made this order (with default for guest users)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Get the event this order is for
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the tickets for this order
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Mark order as paid after successful payment
     */
    public function markAsPaid(string $paymentReference, ?array $paymentData = null): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'payment_reference' => $paymentReference,
            'payment_status' => 'success',
            'payment_data' => $paymentData,
        ]);

        $this->generateTickets();
    }

    /**
     * Mark order as failed
     */
    public function markAsFailed(): void
    {
        $this->update([
            'payment_status' => 'failed',
        ]);
    }

    /**
     * Generate tickets after successful payment
     */
    public function generateTickets(): void
    {
        $ticketDetails = $this->ticket_details ?? [];
        
        if (empty($ticketDetails)) {
            return;
        }

        foreach ($ticketDetails as $ticket) {
            Ticket::create([
                'order_id' => $this->id,
                'event_id' => $this->event_id,
                'ticket_number' => $this->generateTicketNumber(),
                'tier_name' => $ticket['tier_name'] ?? 'Regular',
                'price' => (float) $ticket['price'],
                'seat_number' => $ticket['seat_number'] ?? null,
                'attendee_details' => $ticket['attendee_details'] ?? null,
                'status' => 'sold',
            ]);
        }
    }

    /**
     * Generate unique ticket number
     */
    private function generateTicketNumber(): string
    {
        return 'TKT-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
    }

    /**
     * Check if order is paid
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if order is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'KES ' . number_format((float) $this->total_amount, 2);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($order) {
            if (!isset($order->order_number)) {
                $order->order_number = 'ORD-' . time() . '-' . rand(1000, 9999);
            }
            if (!isset($order->status)) {
                $order->status = self::STATUS_PENDING;
            }
        });
    }
}