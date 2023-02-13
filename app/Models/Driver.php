<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id_number',
        'user_id',
        'license_id',
        'home_address',
        'date_of_last_trip',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<int, string>
     */

    protected $casts = [
        'id_number' => 'int',
        'date_of_last_trip' => 'date',
    ];

    /**
     * Get the User record associated with the Driver.
     *
     * @return BelongsTo
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Vehicles associated with the Driver.
     *
     * @return HasMany
     */

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

/**
     * Get the License record associated with the Driver.
     *
     * @return BelongsTo
     */

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Scope a query to search for a substring in the driver's first name or last name.
     *
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeSubstring(Builder $query, string $name): Builder
    {
        return $query->whereHas('user', fn($q) =>
                $q->where('first_name', 'like', "%$name%" )
                    ->orWhere('last_name', 'like', "%$name%")
        );
    }

    /**
     * Scope a query to search for a substring in the driver's home address.
     *
     * @param Builder $query
     * @param string $address
     * @return Builder
     */
    public function scopeAddress(Builder $query, string $address): Builder
    {
        return $query->where('home_address', 'like', "%$address%");
    }

    /**
     * Scope a query to search for a driver with a vehicle capacity equal to the given capacity.
     *
     * @param Builder $query
     * @param int $capacity
     * @return Builder
     */
    public function scopeVehicleCapacity(Builder $query, int $capacity): Builder
    {
        return $query->whereHas('vehicles', fn($q) =>
            $q->where('passenger_capacity', $capacity)
        );
    }
}
