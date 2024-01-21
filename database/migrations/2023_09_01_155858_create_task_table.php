<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('user_id');
            $table->string('task_name')->nullable();
            $table->string('origin_address');
            $table->string('destination_address');
            $table->string('origin_latitude');
            $table->string('origin_longitude');
            $table->string('destination_latitude');
            $table->string('destination_longitude');
            $table->string('task_status')->default('0');
            $table->string('task_description')->nullable();
            $table->string('assign_from')->nullable();
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
}
