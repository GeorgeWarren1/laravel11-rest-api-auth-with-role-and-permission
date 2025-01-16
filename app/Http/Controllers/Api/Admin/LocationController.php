<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    /**
     * Display a listing of the locations.
     */
    public function index()
    {
        $locations = Location::paginate(10);
        return response()->json($locations);
    }

    /**
     * Store a newly created location in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'location_name' => 'required|string|max:255|unique:locations,location_name',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Handle file upload
        $path = $request->file('location_image')->store('public/locations');

        $location = Location::create([
            'location_image' => $path,
            'location_name' => $request->location_name,
            'address' => $request->address,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'message' => 'Location added successfully',
            'location' => $location,
        ], 201);
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location)
    {
        return response()->json($location);
    }

    /**
     * Update the specified location in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'location_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'location_name' => 'required|string|max:255|unique:locations,location_name,' . $location->id,
            'address' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Handle file upload if provided
        if ($request->hasFile('location_image')) {
            // Delete old image
            Storage::delete($location->location_image);
            $path = $request->file('location_image')->store('public/locations');
            $location->location_image = $path;
        }

        $location->update($request->only([
            'location_name',
            'address',
            'description',
            'latitude',
            'longitude',
        ]));

        return response()->json([
            'message' => 'Location updated successfully',
            'location' => $location,
        ]);
    }

    /**
     * Remove the specified location from storage.
     */
    public function destroy(Location $location)
    {
        // Delete image
        Storage::delete($location->location_image);

        $location->delete();

        return response()->json([
            'message' => 'Location deleted successfully',
        ]);
    }
}
