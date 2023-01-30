<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>
<h1 align="center"> CRUD API </h1>

# Introduction
This is a Laravel CRUD API. It is a simple API that allows you to create, read, update and delete users.
The API is for clients so that they can add drivers to the system, update their information, delete their information, or quickly see a list of drivers or vehicles at a glance.

# Requirements
- Laravel 8.0 or higher is required.
- PHP 8.0 or higher is required.
- Composer 2.5.1 or higher is required.
- MySQL is required.

# Installation
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
- You can also use [DBeaver](https://dbeaver.io/download/) to view the database.

# Usage
- You can use the API to create, read, update and delete drivers.
- You can use the API to create, read, update and delete vehicles.

# API Endpoints
## Driver Endpoints
- `Create Driver` - `POST` - `http://localhost:8000/api/drivers`
- - Create a new driver.
---
- `Read Drivers` - `GET` - `http://localhost:8000/api/drivers`
- - Returns a list of all the drivers with their personal information and vehicle details.
---
- `Read Driver` - `GET` - `http://localhost:8000/api/drivers/{id}`
- - Returns a single driver. This response should include all their personal and vehicle information.
---
- `Update Driver` - `PATCH` - `http://localhost:8000/api/drivers/{id}`
- - Update the driver id or phone number.
- **BODY**:
  - "id_number": _int_,
  - "phone_number": _int_
---
- `Update Driver Details` - `PUT` - `http://localhost:8000/api/drivers/{id}/details`
- - Update the details for a driver.
- **BODY**:
    - "home_address": _string_
    - "first_name": _string_
    - "last_name": _string_
    - "licence_type": One of the following: _"A", "B", "C", "D"_
    - "last_trip_date": _date (YYYY-MM-DD)_
---
- `Delete Driver` - `DELETE` - `http://localhost:8000/api/drivers/{id}`
- - Delete the driver account.
- - Deleting a driver account should automatically delete the details and vehicle information of that driver.
---
- `Delete Driver Details` - `DELETE` - `http://localhost:8000/api/drivers/{id}/details`
- - Delete the driver information.
---

## Vehicle Endpoints
- `Create Vehicle` - `POST` - `http://localhost:8000/api/vehicles`
- - Create a new vehicle.
---
- `Read Vehicles` - `GET` - `http://localhost:8000/api/vehicles`
- - Returns a list of vehicles
---
- `Read Vehicle` - `GET` - `http://localhost:8000/api/drivers/{id}/vehicle`
- - Return the vehicle(s) information for a vehicle when specifying the driver ID.
---
- `Update Vehicle` - `PUT` - `http://localhost:8000/api/vehicles/{id}`
- - Update the vehicle information for a vehicle with id
- **BODY**:
    - "id": _int_
    - "licence_plate_number": _string_
    - "vehicle_make": _string_
    - "vehicle_model": _string_
    - "year": _int_
    - "insured": _boolean_
    - "service_date": _date (YYYY-MM-DD)_
    - "capacity": _int_
---
- `Delete Vehicle` - `DELETE` - `http://localhost:8000/api/vehicles/{id}`
- - Delete the driver vehicle information.

# Data Validation
In order to ensure the integrity and reliability of the data stored in the system, it is important to validate incoming data.
In this project, data validation is performed using Laravel's built-in validation system.
The validation rules applied to incoming data vary depending on the endpoint and the type of data being processed.
