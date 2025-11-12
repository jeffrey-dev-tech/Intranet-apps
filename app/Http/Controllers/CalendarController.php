<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Collection;
class CalendarController extends Controller
{
    /**
     * Fetch all events for FullCalendar
     */
    public function events()
    {
        // Return events in JSON format for FullCalendar
        $events = Event::select('id', 'title', 'start', 'end')->get();
        return response()->json($events);
    }
    public function show($id)
{
    $event = Event::findOrFail($id);
    return response()->json($event);
}

public function destroy($id)
{
    try {
        $event = Event::findOrFail($id); // throws exception if not found
        $event->delete();
        return response()->json(['status' => 'success']);
    } catch (ModelNotFoundException $e) {
        return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
    }
}


    /**
     * Store a new event
     */
public function store(Request $request)
{
    // Validate input
    $request->validate([
        'title' => 'required|string|max:255',
        'agenda' => 'required|string|max:255',
        'start' => 'required|date',
        'end' => 'nullable|date|after_or_equal:start',
        'meeting_room' => 'required_if:agenda,Room|string|max:255',
    ]);

    // Convert to backend timezone (e.g., Asia/Manila)
    $start = Carbon::parse($request->start)->setTimezone(config('app.timezone'));
    $end = $request->end
        ? Carbon::parse($request->end)->setTimezone(config('app.timezone'))
        : $start->copy()->addHour(); // default 1-hour meeting

    // ✅ Check for overlapping bookings
    if ($request->agenda === 'Room') {
        $conflict = Event::where('agenda', 'Room')
            ->where('meeting_room', $request->meeting_room)
            ->where(function ($query) use ($start, $end) {
                $query->where('start', '<', $end)
                      ->where('end', '>', $start);
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'status' => 'error',
                'message' => 'This meeting room is already booked during the selected time range.'
            ], 422);
        }
    }

    // ✅ Create event
    Event::create([
        'title' => $request->title,
        'description' => $request->description,
        'agenda' => $request->agenda,
        'meeting_room' => $request->agenda === 'Room' ? $request->meeting_room : null,
        'pic' => auth()->user()->email,
        'start' => $start,
        'end' => $end,
    ]);

    return response()->json(['status' => 'success']);
}


public function getHolidayJson()
{
    $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
    $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

    $holidays = Event::where('agenda', 'holiday')
        ->whereDate('start', '>=', $startOfMonth)
        ->whereDate('start', '<=', $endOfMonth)
        ->get(['id', 'title', 'start', 'end']);

    // Ensure it's a Laravel Collection (optional since get() returns a collection)
    $holidays = new Collection($holidays);

    return response()->json($holidays);
}



}
