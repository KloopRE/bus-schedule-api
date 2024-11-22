<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::all();
        return response()->json($routes);
    }

    public function show($id)
    {
        $route = Route::find($id);
        if ($route) {
            return response()->json($route);
        } else {
            return response()->json(['message' => 'Route not found'], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'direction' => 'required|string|max:255',
            ]);

            $route = Route::create($validated);
            return response()->json($route, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'direction' => 'required|string|max:255',
        ]);

        $route = Route::find($id);
        if ($route) {
            $route->update($validated);
            return response()->json($route);
        }
        return response()->json(['message' => 'Route not found'], 404);
    }

    public function destroy($id)
    {
        $route = Route::find($id);
        if ($route) {
            $route->delete();
            return response()->json(['message' => 'Route deleted']);
        }
        return response()->json(['message' => 'Route not found'], 404);
    }
}


