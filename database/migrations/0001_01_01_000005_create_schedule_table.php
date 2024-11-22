<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes');
            $table->foreignId('stop_id')->constrained('stops');
            $table->time('arrival_time');
            $table->time('departure_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule');
    }
}
