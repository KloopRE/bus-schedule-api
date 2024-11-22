<?php

namespace Database\Factories;

use App\Models\Stop;
use Illuminate\Database\Eloquent\Factories\Factory;

class StopFactory extends Factory
{
    protected $model = Stop::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'location' => $this->faker->address
        ];
    }
}

