<?php
namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarEventController extends Controller
{
    public function index()
    {
        $events = CalendarEvent::orderBy('start_time', 'asc')->get(); // Fetch all events from the database
        return response()->json($events); // Return the events as JSON
    }
}