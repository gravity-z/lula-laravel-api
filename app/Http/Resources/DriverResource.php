<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'id_number' => $this->id_number,
//            'phone_number' => $this->user->phone_number,
//            'details' => [
//                'id' => $this->user->id,
//                'home_address' => $this->user->home_address,
//                'first_name' => $this->user->first_name,
//                'last_name' => $this->user->last_name,
//                'license_type' => $this->license->type,
//                'last_trip_date' => $this->date_of_last_trip,
//            ],
//            'vehicle' => VehicleResource::collection($this->whenLoaded('vehicles')),
        ];
    }
}
