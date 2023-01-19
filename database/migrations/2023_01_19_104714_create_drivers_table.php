<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->string('id_number')->primary();
            $table->foreignId('user_id')
                ->constrained()
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreignId('license_id')
                ->constrained()
                ->references('id')
                ->on('licenses')
                ->onDelete('cascade');
            $table->string('home_address');
            $table->date('date_of_last_trip');
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
        Schema::dropIfExists('drivers');
    }
}
