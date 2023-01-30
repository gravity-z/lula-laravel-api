<?php

namespace App\Providers;

use App\Api\ApiResponse;
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

        Response::macro('update', function ($status, $success, $message, $data = null) {
            return new ApiResponse($status, $success, $message, $data);
        });
    }
}
