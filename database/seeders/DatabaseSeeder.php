<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Stop;
use App\Models\Schedule;
use App\Models\RouteStop;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        RouteStop::factory(10)->create();

        Schedule::factory(20)->create();
    }
}
