<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Stop;
use App\Models\Schedule;
use Carbon\Carbon;

class BusController extends Controller
{
    public function findBus(Request $request)
    {
        try {
            $validated = $request->validate([
                'from' => 'required|integer|exists:stops,id',
                'to' => 'required|integer|exists:stops,id',
            ]);

            $fromStop = $validated['from'];
            $toStop = $validated['to'];

            $routes = Route::whereHas('stops', function ($query) use ($fromStop, $toStop) {
                $query->whereIn('stops.id', [$fromStop, $toStop]);
            })
            ->get();

            if ($routes->isEmpty()) {
                return response()->json([
                    'status' => 'no_routes',
                    'message' => 'Нет маршрутов, проходящих через выбранные остановки.',
                    'from_stop' => $fromStop,
                    'to_stop' => $toStop,
                ], 404);
            }

            \Log::info('Маршруты: ', $routes->toArray());

            $result = [];

            foreach ($routes as $route) {
                $schedule = Schedule::where('route_id', $route->id)
                    ->where('stop_id', $fromStop)
                    ->where('arrival_time', '>', now())
                    ->orderBy('arrival_time')
                    ->limit(3)
                    ->get(['arrival_time', 'departure_time']);

                if ($schedule->isNotEmpty()) {
                    $result[] = [
                        'route_name' => $route->name,
                        'from_stop' => $route->stops->firstWhere('id', $fromStop)->name,
                        'to_stop' => $route->stops->firstWhere('id', $toStop)->name,
                        'arrival_times' => $schedule->pluck('arrival_time')
                            ->map(function ($time) {
                                return Carbon::parse($time)->format('H:i:s');
                            })->toArray(),
                        'departure_times' => $schedule->pluck('departure_time')
                            ->map(function ($time) {
                                return Carbon::parse($time)->format('H:i:s');
                            })->toArray(),
                    ];
                }
            }

            if (empty($result)) {
                return response()->json([
                    'status' => 'no_schedule',
                    'message' => 'Не найдено расписания для выбранных маршрутов.',
                ], 404);
            }

            \Log::info('Результат: ', $result);

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error occurred.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error during find bus request: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Произошла ошибка на сервере'
            ], 500);
        }
    }

}
