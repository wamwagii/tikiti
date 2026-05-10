<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable([
    'name', 
    'email', 
    'password',
    'phone',
    'national_id',
    'role',
    'is_active',
    'last_login_at',
    'password_changed_at',
    'two_factor_secret',
    'two_factor_enabled'
])]
#[Hidden(['password', 'remember_token', 'two_factor_secret'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Check if user can access Filament admin panel
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && in_array($this->role, ['admin', 'event_manager']);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is event manager
     */
    public function isEventManager(): bool
    {
        return $this->role === 'event_manager';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Disable user account
     */
    public function disableAccount(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Enable user account
     */
    public function enableAccount(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Force password change on next login
     */
    public function forcePasswordChange(): void
    {
        $this->update(['password_changed_at' => null]);
    }

    /**
     * Check if user needs to change password
     */
    public function needsPasswordChange(): bool
    {
        return is_null($this->password_changed_at);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Enable two-factor authentication
     */
    public function enableTwoFactor(): void
    {
        $this->update(['two_factor_enabled' => true]);
    }

    /**
     * Disable two-factor authentication
     */
    public function disableTwoFactor(): void
    {
        $this->update(['two_factor_enabled' => false]);
    }

    /**
     * Get user's full name with role badge
     */
    public function getDisplayNameAttribute(): string
    {
        $roleIcon = match($this->role) {
            'admin' => '👑 ',
            'event_manager' => '📅 ',
            'customer' => '🎫 ',
            default => '',
        };
        
        return $roleIcon . $this->name;
    }

    /**
     * Check if user has active account
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Scope for active users only
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive users only
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope for specific role
     */
    public function scopeRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    /**
     * Get user's total spent on tickets
     */
    public function getTotalSpentAttribute(): float
    {
        return (float) $this->orders()->sum('total_amount');
    }

    /**
     * Get user's ticket purchase count
     */
    public function getTicketCountAttribute(): int
    {
        return (int) $this->tickets()->count();
    }

    /**
     * Get user's orders (has many relationship)
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user's tickets through orders (has many through relationship)
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Order::class, 'user_id', 'order_id');
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot(): void
    {
        parent::boot();

        // When creating a user, set default values
        static::creating(function ($user) {
            if (!isset($user->role)) {
                $user->role = 'customer';
            }
            if (!isset($user->is_active)) {
                $user->is_active = true;
            }
        });
    }
}