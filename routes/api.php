<?php

use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::delete('drivers/{id}/details', DriverController::class . '@destroy');

Route::resource('drivers', DriverController::class);

Route::resource('vehicles', VehicleController::class);

Route::get('driver/{id}/vehicle', VehicleController::class . '@indexByDriverId');

Route::patch('driver/{id}', DriverController::class . '@patch');

Route::put('driver/{id}/details', DriverController::class . '@update');
