<?php

namespace App\Providers;

use App\Api\ApiResponse;
use App\Api\ApiUpdateResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Remove the wrapping of the JSON response e.g. {"data": {...}}
//        JsonResource::withoutWrapping();

        Response::macro('success', function ($data = null) {
            return new ApiResponse($data);
        });

        Response::macro('update', function ($status, $success, $message, $data) {
            return new ApiUpdateResponse($status, $success, $message, $data);
        });
    }
}
