<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouteStop;
use App\Models\Route;
use App\Models\Stop;

class RouteStopController extends Controller
{
    public function index($routeId)
    {
        $routeStops = RouteStop::with('stop')
            ->where('route_id', $routeId)
            ->orderBy('order')
            ->get();

        return response()->json($routeStops);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'stop_id' => 'required|exists:stops,id',
            'order' => 'required|integer|min:0',
        ]);

        $routeStop = RouteStop::create($validated);

        return response()->json(['message' => 'Остановка добавлена к маршруту', 'data' => $routeStop], 201);
    }
}

