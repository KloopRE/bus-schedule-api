<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_routes()
    {
        $response = $this->get('/routes/');
        $response->assertStatus(200);
    }

    public function test_can_create_a_route()
    {
        $data = [
            'name' => 'New Route',
            'direction' => 'New Direction'
        ];

        $response = $this->postJson('/routes/', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'New Route',
            'direction' => 'New Direction'
        ]);
    }

    public function test_can_get_all_routes()
    {
        Route::factory()->count(3)->create();

        $response = $this->getJson('/routes/');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_show_a_route()
    {
        $route = Route::factory()->create();

        $response = $this->getJson('/routes/' . $route->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $route->name,
            'direction' => $route->direction,
        ]);
    }

    public function test_can_update_a_route()
    {
        $route = Route::factory()->create();
        $data = [
            'name' => 'Updated Route',
            'direction' => 'Updated Direction'
        ];

        $response = $this->putJson('/routes/' . $route->id, $data);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Updated Route',
            'direction' => 'Updated Direction'
        ]);
    }

    public function test_can_delete_a_route()
    {
        $route = Route::factory()->create();

        $response = $this->deleteJson('/routes/' . $route->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('routes', ['id' => $route->id]);
    }
}
