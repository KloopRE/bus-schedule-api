<?php

namespace Database\Factories;

use App\Models\RouteStop;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteStopFactory extends Factory
{
    protected $model = RouteStop::class;

    public function definition()
    {
        return [
            'route_id' => Route::factory(),
            'stop_id' => Stop::factory(),
            'order' => $this->faker->numberBetween(1, 100)
        ];
    }
}

