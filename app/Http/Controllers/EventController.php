<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        // Ensure event is published
        if ($event->status !== 'published') {
            abort(404);
        }
        
        return view('event.show', compact('event'));
    }
}