<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    public function reverse(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        // Validate inputs
        if (!$lat || !$lon) {
            return response()->json(['error' => 'Missing lat/lon'], 400);
        }

        // Call Nominatim
        $response = Http::withHeaders([
            'User-Agent' => 'MyLaravelApp/1.0'
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $lat,
            'lon' => $lon,
            'format' => 'json'
        ]);

        return $response->json();
    }
}
