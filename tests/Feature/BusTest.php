<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Route;
use App\Models\Stop;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест успешного поиска автобуса.
     *
     * @return void
     */
    public function test_find_bus_successful(): void
    {
        $fromStop = Stop::factory()->create();
        $toStop = Stop::factory()->create();

        $route = Route::factory()->create();
        $route->stops()->attach([$fromStop->id, $toStop->id]);

        $schedule = Schedule::factory()->create([
            'route_id' => $route->id,
            'stop_id' => $fromStop->id,
            'arrival_time' => now()->addMinutes(10),
        ]);

        $response = $this->getJson("/api/find-bus?from={$fromStop->id}&to={$toStop->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                [
                    'route_name' => $route->name,
                    'arrival_times' => [
                        $schedule->arrival_time->format('H:i:s'),
                    ],
                ]
            ]
        ]);
    }


    /**
     * Тест, когда маршрутов не найдено.
     *
     * @return void
     */
    public function test_find_bus_no_routes(): void
    {
        $fromStop = Stop::factory()->create();
        $toStop = Stop::factory()->create();

        $response = $this->getJson("/api/find-bus?from={$fromStop->id}&to={$toStop->id}");

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'no_routes',
            'message' => 'Нет маршрутов, проходящих через выбранные остановки.',
        ]);
    }

    /**
     * Тест для проверки ошибок валидации.
     *
     * @return void
     */
    public function test_find_bus_validation_error(): void
    {
        $response = $this->getJson('/api/find-bus?from=999&to=999');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['from', 'to']);
    }
}
