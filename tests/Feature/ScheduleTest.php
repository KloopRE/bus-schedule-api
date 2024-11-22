<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Route;
use App\Models\Stop;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_schedule()
    {
        $route = Route::factory()->create();
        $stop = Stop::factory()->create();

        $data = [
            'route_id' => $route->id,
            'stop_id' => $stop->id,
            'arrival_time' => '08:00:00',
            'departure_time' => '08:05:00',
        ];

        $response = $this->postJson('/schedule/', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['arrival_time' => '08:00:00']);
    }


    public function test_can_get_all_schedule()
    {
        Schedule::factory()->count(3)->create();

        $response = $this->getJson('/schedule/');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_show_a_schedule()
    {
        $schedule = Schedule::factory()->create();

        $response = $this->getJson('/schedule/' . $schedule->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['arrival_time' => $schedule->arrival_time]);
    }

    public function test_can_update_a_schedule()
    {
        $schedule = Schedule::factory()->create();
        $data = [
            'arrival_time' => '09:00:00',
            'departure_time' => '09:05:00'
        ];

        $response = $this->putJson('/schedule/' . $schedule->id, $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['arrival_time' => '09:00:00']);
    }

    public function test_can_delete_a_schedule()
    {
        $schedule = Schedule::factory()->create();

        $response = $this->deleteJson('/schedule/' . $schedule->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('schedule', ['id' => $schedule->id]);
    }
}
