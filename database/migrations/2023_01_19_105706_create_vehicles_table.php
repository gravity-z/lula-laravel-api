<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate_number')->unique();
            $table->string('vehicle_make');
            $table->string('vehicle_model');
            $table->bigInteger('model_year');
            $table->boolean('insured');
            $table->date('date_of_last_service')->nullable();
            $table->integer('passenger_capacity');
            $table->foreignId('driver_id')
                ->constrained()
                ->references('id')
                ->on('drivers')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
