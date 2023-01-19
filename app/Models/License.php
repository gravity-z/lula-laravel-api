<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    /**
     * constants for license type
     * @var string
     */
    const LICENSE_TYPE_A = 'A';
    const LICENSE_TYPE_B = 'B';
    const LICENSE_TYPE_C = 'C';
    const LICENSE_TYPE_D = 'D';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'license_type',
    ];

//    /**
//     * Attributes that should be cast.
//     *
//     * @var array
//     */
//
//    protected $casts = [
//        'license_type' => 'enum',
//    ];

    /**
     * Get the Driver records associated with the License type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}
