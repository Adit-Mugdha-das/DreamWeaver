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

        // Build the Places API Nearby Search request
        $response = Http::get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'location' => "$lat,$lng",
            'radius'   => 10000, // 5km radius
            'type'     => 'hospital', // ensure hospital results
            'keyword'  => 'mental health doctor psychiatrist', // extra filtering
            'key'      => env('GOOGLE_MAPS_API_KEY'),
        ]);

        $json = $response->json();

        // Handle errors from Google API
        if (!isset($json['results'])) {
            return response()->json([
                'ok' => false,
                'status' => $json['status'] ?? 'ERROR',
                'message' => $json['error_message'] ?? 'Unknown error from Google Places API'
            ], 500);
        }

        // Return only the relevant details
        $results = collect($json['results'])->map(function ($place) {
            return [
                'name'     => $place['name'] ?? '',
                'address'  => $place['vicinity'] ?? '',
                'rating'   => $place['rating'] ?? null,
                'location' => $place['geometry']['location'] ?? null,
            ];
        });

        return response()->json([
            'ok'      => true,
            'results' => $results,
        ]);
    }
}
