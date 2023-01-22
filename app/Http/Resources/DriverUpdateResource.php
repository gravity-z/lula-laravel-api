<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $id_number
 * @property mixed $phone_number
 * @property mixed $user
 */
class DriverUpdateResource extends JsonResource
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
        ];
    }
}
