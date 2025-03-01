<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusLocation;
use App\Services\GeofencingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BusLocationController extends Controller
{
    private $geofencingService;

    public function __construct(GeofencingService $geofencingService)
    {
        $this->geofencingService = $geofencingService;
    }

    public function updateLocation(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
        ]);

        $location = $bus->locations()->update([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'speed' => $validated['speed'] ?? null,
            'location_timestamp' => now(),
        ]);


        try {
            $this->geofencingService->checkProximity($bus, $validated['latitude'], $validated['longitude']);
        } catch (\Exception $e) {
            Log::error('Geofencing Error: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Location updated successfully',
            'location' => $location
        ], 200);
    }
}
