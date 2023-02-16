<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'license_plate_number',
        'vehicle_make',
        'vehicle_model',
        'model_year',
        'insured',
        'date_of_last_service',
        'passenger_capacity',
        'driver_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'model_year' => 'integer',
        'insured' => 'boolean',
        'date_of_last_service' => 'date',
        'passenger_capacity' => 'integer',
    ];

    /**
     * Get the Driver record associated with the Vehicle.
     *
     * @return BelongsTo
     */

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id_number', 'id_number');
    }

    /**
     * Scope a query to only include vehicles of a given make.
     *
     * @param Builder $query
     * @param string $make
     * @return Builder
     */
    public function scopeVehicleMake(Builder $query, string $make): Builder
    {
        return $query->where('vehicle_make', $make);
    }

    /**
     * Scope a query to only include vehicles where the date of last service is less than or equal to the date provided.
     *
     * @param Builder $query
     * @param string $date
     * @return Builder
     */
    public function scopeServiceDate(Builder $query, string $date): Builder
    {
        return $query->where('date_of_last_service', '<=', $date);
    }

    /**
     * Scope a query to only include vehicles of a given age/model_year.
     *
     * @param Builder $query
     * @param int $age
     * @return Builder
     */
    public function scopeModelAge(Builder $query, int $age): Builder
    {
        return $query->where('model_year', '=', now()->subYears($age)->year);
    }
}
