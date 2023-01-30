<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Introduction
This is a Laravel CRUD API. It is a simple API that allows you to create, read, update and delete users.
The API is for clients so that they can add drivers to the system, update their information, delete their information, or quickly see a list of drivers or vehicles at a glance.

## Requirements
- Laravel 8.0 or higher is required.
- PHP 8.0 or higher is required.
- Composer 2.5.1 or higher is required.
- MySQL is required.

## Installation
- Clone the repository to your local machine.
- Install [Composer](https://getcomposer.org/download/) for your operating system.
- Install [XAMPP](https://www.apachefriends.org/) for your operating system.
- Start the Apache and MySQL modules in XAMPP.
- Run `composer install` to install the dependencies.
- Run `php artisan migrate` to create the database tables.
- Run `php artisan db:seed` to seed the database tables.
- Run `php artisan serve` to start the server.
- Open your browser and go to `http://localhost:8000/` to view the API.
- You can also use [Postman](https://www.postman.com/downloads/) to test the API.
- You can also use [Insomnia](https://insomnia.rest/download/) to test the API.
- You can also use [DBeaver](https://dbeaver.io/download/) to view the database.

## Usage
- You can use the API to create, read, update and delete drivers.
- You can use the API to create, read, update and delete vehicles.

## API Endpoints
- `Create Driver` - `POST` - `http://localhost:8000/api/drivers`
- - Create a new driver.
---
- `Create Vehicle` - `POST` - `http://localhost:8000/api/vehicles`
- - Create a new vehicle.
---
- `Read Drivers` - `GET` - `http://localhost:8000/api/drivers`
- - Returns a list of all the drivers with their personal information and vehicle details.
---
- `Read Vehicles` - `GET` - `http://localhost:8000/api/vehicles`
- - Returns a list of vehicles
---
- `Read Driver` - `GET` - `http://localhost:8000/api/drivers/{id}`
- - Returns a single driver. This response should include all their personal and vehicle information.
---
- `Read Vehicle` - `GET` - `http://localhost:8000/api/drivers/{id}/vehicle`
- - Return the vehicle(s) information for a vehicle when specifying the driver ID.
---
- `Update Driver` - `PATCH` - `http://localhost:8000/api/drivers/{id}`
- - Update the driver id or phone number.
- - BODY:
    {
    "id_number": 23443223777,
    "phone_number": 940105432
    }
---
- `Update Driver Details` - `PUT` - `http://localhost:8000/api/drivers/{id}/details`
- - Update the details for a driver.
- - BODY:
{
"home_address": "4 LULA road, Cape Town, Woodstock, South Africa, 8001",
"first_name": "John",
"last_name": "Doe",
"licence_type": "B",
"last_trip_date": "2022-10-12T08:18:09.000000Z"
}
---
- `Update Vehicle` - `PUT` - `http://localhost:8000/api/vehicles/{id}`
- - Update the vehicle information for a vehicle with id
- - BODY:
    {
    "id": 32,
    "licence_plate_number": "CJ3443121234",
    "vehicle_make": "HONDA",
    "vehicle_model": "ACCORD",
    "year": 2001,
    "insured": true,
    "service_date": "2022-10-12T08:18:09.000000Z",
    "capacity": 6
    }
---
- `Delete Driver` - `DELETE` - `http://localhost:8000/api/drivers/{id}`
- - Delete the driver account.
- - Deleting a driver account should automatically delete the details and vehicle information of that driver.
---
- `Delete Driver Details` - `DELETE` - `http://localhost:8000/api/drivers/{id}/details`
- - Delete the driver information.
- `Delete Vehicle` - `DELETE` - `http://localhost:8000/api/vehicles/{id}`
- - Delete the driver vehicle information.
