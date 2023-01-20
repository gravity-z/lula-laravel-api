<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $id
 * @property mixed $id_number
 * @property mixed $user
 * @property mixed $home_address
 * @property mixed $license
 * @property mixed $date_of_last_trip
 */
class DriverResource extends JsonResource
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
            'id_number' => $this->id_number,
            'phone_number' => $this->user->phone_number,
            'details' => [
                'id' => $this->user->id,
                'home_address' => $this->home_address,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'license_type' => $this->license->license_type,
                'last_trip_date' => $this->date_of_last_trip,
            ],
            'vehicle' => VehicleResource::collection($this->whenLoaded('vehicles')),
        ];
    }
}
