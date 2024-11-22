<?php

namespace Tests\Feature;

use App\Models\RouteStop;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteStopTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_stop_creation()
    {
        $route = Route::create([
            'name' => 'Route 1',
            'direction' => 'North',
        ]);

        $stop = Stop::create([
            'name' => 'Stop 1',
            'location' => 'Some Location',
        ]);

        $routeStop = RouteStop::create([
            'route_id' => $route->id,
            'stop_id' => $stop->id,
            'order' => 1,
        ]);

        $this->assertDatabaseHas('route_stops', [
            'id' => $routeStop->id,
            'route_id' => $routeStop->route_id,
            'stop_id' => $routeStop->stop_id,
            'order' => $routeStop->order,
        ]);
    }

    public function test_route_stop_validation_fails_without_required_fields()
    {
        $response = $this->postJson('/route-stops', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['route_id', 'stop_id', 'order']);
    }
}
