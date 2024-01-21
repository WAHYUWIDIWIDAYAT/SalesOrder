<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //BUAT TABLE districts
        Schema::create('districts', function (Blueprint $table) {
            //DENGAN FIELD DIBAWAH INI
            $table->bigIncrements('id');
            $table->unsignedBigInteger('province_id'); //FIELD INI AKAN MERUJUK KE TABLE PROVINCES
            $table->foreign('province_id')->references('id')->on('provinces')->onCascade('delete');
            $table->unsignedBigInteger('city_id'); // FIELD INI AKAN MERUJUK KE TABLE CITIES
            $table->foreign('city_id')->references('id')->on('cities')->onCascade('delete');
            $table->string('name');
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
        Schema::dropIfExists('districts');
    }
}
