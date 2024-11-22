<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use Illuminate\Http\Request;

class StopController extends Controller
{
    public function index()
    {
        $stops = Stop::all();
        return response()->json($stops);
    }

    public function show($id)
    {
        $stop = Stop::find($id);
        if ($stop) {
            return response()->json($stop);
        } else {
            return response()->json(['message' => 'Stop not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $stop = Stop::create($validated);

        return response()->json($stop, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $stop = Stop::find($id);

        if (!$stop) {
            return response()->json(['message' => 'Stop not found'], 404);
        }

        $stop->update($validated);

        return response()->json($stop, 200);
    }

    public function destroy($id)
    {
        $stop = Stop::find($id);

        if (!$stop) {
            return response()->json(['message' => 'Stop not found'], 404);
        }

        $stop->delete();

        return response()->json(null, 200);
    }

}
