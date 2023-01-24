<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $license_plate_number
 * @property mixed $vehicle_make
 * @property mixed $vehicle_model
 * @property mixed $model_year
 * @property mixed $insured
 * @property mixed $date_of_last_service
 * @property mixed $passenger_capacity
 * @property mixed $driver_id_number
 */
class VehicleResource extends JsonResource
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
            'license_plate_number' => $this->license_plate_number,
            'vehicle_make' => $this->vehicle_make,
            'vehicle_model' => $this->vehicle_model,
            'year' => $this->model_year,
            'insured' => $this->insured,
            'service_date' => $this->date_of_last_service,
            'capacity' => $this->passenger_capacity,
        ];
    }
}
