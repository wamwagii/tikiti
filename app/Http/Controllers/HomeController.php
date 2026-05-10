<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get upcoming events (future dates)
        $upcomingEvents = Event::with('venue')
            ->where('start_date', '>', now())
            ->where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();
        
        // Get featured events
        $featuredEvents = Event::with('venue')
            ->where('featured', true)
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();
        
        // Get football matches
        $footballMatches = Event::with('venue')
            ->where('type', 'football')
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();
        
        // Get concerts
        $concerts = Event::with('venue')
            ->where('type', 'concert')
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();
        
        // Get popular venues
        $popularVenues = Venue::withCount('events')
            ->where('is_active', true)
            ->orderBy('events_count', 'desc')
            ->take(4)
            ->get();
        
        return view('home', compact(
            'upcomingEvents', 
            'featuredEvents', 
            'footballMatches', 
            'concerts', 
            'popularVenues'
        ));
    }
}