<?php

namespace App\Models;

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
        'driver_id',
        'license_id',
        'home_address',
        'date_of_last_trip',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'id_number' => 'int',
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
}
