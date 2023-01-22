<?php

namespace App\Api;

use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * The response data.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new API success response instance.
     *
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;

        parent::__construct($this->data);
    }
}
