<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $id
 * @property mixed $home_address
 * @property mixed $user
 * @property mixed $license
 * @property mixed $date_of_last_trip
 */
class DriverDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'home_address' => $this->home_address,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'licence_type' => $this->license->license_type,
            'last_trip_date' => $this->date_of_last_trip,
        ];
    }
}
