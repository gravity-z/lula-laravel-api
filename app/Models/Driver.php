<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_number';
    public $incrementing = false;

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
        'status',
    ];

/**
     * Get the User record associated with the Driver.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Vehicles associated with the Driver.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

/**
     * Get the License record associated with the Driver.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
