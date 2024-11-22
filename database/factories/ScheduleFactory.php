<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition()
    {
        return [
            'route_id' => Route::factory(),
            'stop_id' => Stop::factory(),
            'arrival_time' => $this->faker->time(),
            'departure_time' => $this->faker->time()
        ];
    }
}

