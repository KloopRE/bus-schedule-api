<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedule = Schedule::all();
        return response()->json($schedule);
    }

    public function show($id)
    {
        $schedule = Schedule::find($id);
        if ($schedule) {
            return response()->json($schedule);
        } else {
            return response()->json(['message' => 'Schedule not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'stop_id' => 'required|exists:stops,id',
            'arrival_time' => 'required|date_format:H:i:s',
            'departure_time' => 'required|date_format:H:i:s',
        ]);

        $schedule = new Schedule();
        $schedule->route_id = $validated['route_id'];
        $schedule->stop_id = $validated['stop_id'];
        $schedule->arrival_time = $validated['arrival_time'];
        $schedule->departure_time = $validated['departure_time'];
        $schedule->save();

        return response()->json($schedule, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'arrival_time' => 'required|date_format:H:i:s',
            'departure_time' => 'required|date_format:H:i:s',
        ]);

        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        $schedule->update($validated);

        return response()->json($schedule, 200);
    }

    public function destroy($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        $schedule->delete();

        return response()->json(null, 200);
    }

}

