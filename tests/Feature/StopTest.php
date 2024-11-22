<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Stop;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StopTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_stop()
    {
        $data = [
            'name' => 'Test Stop',
            'location' => 'Test location'
        ];

        $response = $this->postJson('/stops/', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Test Stop',
            'location' => 'Test location'
        ]);
    }

    public function test_can_get_all_stops()
    {
        Stop::factory()->count(3)->create();

        $response = $this->getJson('/stops/');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_show_a_stop()
    {
        $stop = Stop::factory()->create();

        $response = $this->getJson('/stops/' . $stop->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $stop->name]);
        $response->assertJsonFragment([
            'name' => $stop->name,
            'location' => $stop->location
        ]);
    }

    public function test_can_update_a_stop()
    {
        $stop = Stop::factory()->create();
        $data = [
            'name' => 'Updated Stop',
            'location' => 'Update location'
        ];

        $response = $this->putJson('/stops/' . $stop->id, $data);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Updated Stop',
            'location' => 'Update location'
        ]);
    }

    public function test_can_delete_a_stop()
    {
        $stop = Stop::factory()->create();

        $response = $this->deleteJson('/stops/' . $stop->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('stops', ['id' => $stop->id]);
    }
}
