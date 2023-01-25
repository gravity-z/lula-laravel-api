<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'date_of_last_service' => 'datetime',
        'passenger_capacity' => 'integer',
    ];

    /**
     * Get the Driver record associated with the Vehicle.
     *
     * @return BelongsTo
     */

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id_number', 'id_number');
    }
}
