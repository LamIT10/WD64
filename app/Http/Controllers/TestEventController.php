<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestEventController extends Controller
{
    /**
     * Display the test event page.
     */
    public function index()
    {
        return Inertia::render('TestEvent/Index');
    }

    /**
     * Broadcast a test event.
     */
    public function broadcast(Request $request)
    {
        $message = $request->input('message', 'This is a test event from the controller');
        event(new TestEvent($message));
        
        return response()->json(['success' => true, 'message' => 'Event broadcasted successfully']);
    }
}
