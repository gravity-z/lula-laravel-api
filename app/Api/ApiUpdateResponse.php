<?php

namespace App\Api;

use Illuminate\Http\JsonResponse;

class ApiUpdateResponse extends JsonResponse
{
    /**
     * The status text.
     *
     * @var string
     */

    protected string $status;

    /**
     * The response success status.
     *
     * @var bool
     */
    protected bool $success;

    /**
     * The response message.
     *
     * @var string
     */
    protected string $message;

    /**
     * The response data.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new API response instance.
     *
     * @param string $status
     * @param bool $success
     * @param string $message
     * @param mixed $data
     */
    public function __construct($status, $success, $message, $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->success = $success;

        $payload = [
            'status' => $this->status,
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
        ];

        parent::__construct([$payload]);
    }
}
