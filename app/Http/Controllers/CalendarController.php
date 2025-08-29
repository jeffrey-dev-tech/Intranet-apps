<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    $event = Event::find($id);
    if ($event) {
        $event->delete();
        return response()->json(['status' => 'success']);
    }
    return response()->json(['status' => 'error'], 404);
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
        ]);

        // Create event
        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'agenda' => $request->agenda,
            'pic' => auth()->user()->email,
            'start' => $request->start,
            'end' => $request->end,
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
