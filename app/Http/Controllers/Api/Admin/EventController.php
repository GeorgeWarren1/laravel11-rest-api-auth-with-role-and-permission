<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        $events = Event::paginate(10);
        return response()->json($events);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'event_title' => 'required|string|max:255',
            'event_type' => 'required|in:Love Kitchen,OtherType1,OtherType2',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Handle file upload
        $path = $request->file('event_image')->store('public/events');

        $event = Event::create([
            'event_image' => $path,
            'event_title' => $request->event_title,
            'event_type' => $request->event_type,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Event added successfully',
            'event' => $event,
        ], 201);
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return response()->json($event);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'event_title' => 'required|string|max:255',
            'event_type' => 'required|in:Love Kitchen,OtherType1,OtherType2',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Handle file upload if provided
        if ($request->hasFile('event_image')) {
            // Delete old image
            Storage::delete($event->event_image);
            $path = $request->file('event_image')->store('public/events');
            $event->event_image = $path;
        }

        $event->update($request->only([
            'event_title',
            'event_type',
            'date',
            'time',
            'location',
            'description',
        ]));

        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event,
        ]);
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event)
    {
        // Delete image
        Storage::delete($event->event_image);

        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully',
        ]);
    }
}
