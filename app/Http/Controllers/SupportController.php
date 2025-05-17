<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SupportController extends Controller
{
    public function showNearbySupport()
    {
        return view('support.nearby');
    }

    public function getNearby(Request $request)
    {
        $lat = $request->latitude;
        $lng = $request->longitude;

        $response = Http::get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'location' => "$lat,$lng",
            'radius' => 5000,
            'keyword' => 'mental health hospital doctor psychiatrist',
            'key' => env('GOOGLE_MAPS_API_KEY'),
        ]);

        return response()->json($response->json()['results']);
    }
}
